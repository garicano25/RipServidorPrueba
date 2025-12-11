var datatable_caracteristicas = null;
var datatable_catalogo = null;
var datatable_catalogo2campos = null;
var datatable_agentes = null;
var datatable_organizacion = null;
var datatable_opciones = null;
var datatable_epp = null;
var datatable_cargos = null;
var datatable_formatos = null;
var datatable_conclusiones = null;


///// DECLARAR LAS DATETABLE GLOBALES

var datatable_regionanatomica = null;
var datatable_claveyepp = null;
var datatable_marcas = null;
var datatable_normasnacionales = null;
var datatable_normasinternacionales = null;
var datatable_tallas = null;
var datatable_clasificacionriesgp = null;
var datatable_tipouso = null;
var datatable_epp = null;
var TablaEppDocumentos = null;
var datatable_entidades = null;


var caracteristicasespecificos = [];
var materialesutilizados = [];
var notasnomnacionales = [];
var apartandonomnacionales = [];
var notasnominternacionales = [];
var apartandonominternacionales = [];
var certificacionesadicionales = [];
var restriccionesdeuso = [];
var recomendacionalmacenamiento = [];
var recomendacionlimpieza = [];
var recomendaciondisposicion = [];
var nomnacionales = [];


var CAT_EPP_ID = 0;

var catalogo = 0;

// Load CATALOGOS PROVEEDORES
$(document).ready(function()
{
	mostrar_catalogo(1);

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
        $("#cat_"+(key+1)).addClass("text-secondary");
    });
    
   	switch (catalogo){

        case 1:
            // activar menu
            $("#titulo_tabla").html('Catálogo [EPP - NOM-017-STPS-2024]');
            $("#tr_1").addClass("active");
			$("#cat_1").addClass("text-info");

            // Inicializar tabla
            if(datatable_epp != null)
            {
                datatable_epp.destroy();
                datatable_epp = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_epp" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Foto EPP</th>'+
                                                '<th>Región anatómica</th>'+
                                                '<th>Clave y EPP</th>'+
                                                '<th>Nombre</th>' +
                                                '<th>Modelo</th>'+
                                                '<th>Marca</th>'+
                                                '<th>Riesgo</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_epp(catalogo);
            break;
        case 2:
           
            // activa menu
            $("#titulo_tabla").html('Catálogo [Región Anatómica]');
            $("#tr_2").addClass("active");
			$("#cat_2").addClass("text-info");

            // Inicializar tabla
            if(datatable_regionanatomica != null)
            {
                datatable_regionanatomica.destroy();
                datatable_regionanatomica = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_regionanatomica" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Nombre región anatómica</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_region_anatomica(catalogo);
            break;
        case 3:

            // activa menu
            $("#titulo_tabla").html('Catálogo [Clave y EPP]');
            $("#tr_3").addClass("active");
			$("#cat_3").addClass("text-info");

            // Inicializar tabla
            if(datatable_claveyepp != null)
            {
                datatable_claveyepp.destroy();
                datatable_claveyepp = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_claveyepp" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Región anatómica</th>' +
                                                '<th>Clave y EPP </th>'+
                                                '<th>Tipo de riesgo</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

                  tabla_claveyepp(catalogo);
        
            break;
        case 4:
        
            // activa menu
            $("#titulo_tabla").html('Catálogo [Nombres de las marcas]');
            $("#tr_4").addClass("active");
            $("#cat_4").addClass("text-info");

            // Inicializar tabla
            if(datatable_marcas != null)
            {
                datatable_marcas.destroy();
                datatable_marcas = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_marcas" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>Nombre marca</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_marcas(catalogo);
            break;
        case 5:
            
            // activa menu
			$("#titulo_tabla").html('Catálogo [Norma/estándar nacionales]');
			$("#tr_5").addClass("active");
			$("#cat_5").addClass("text-info");

            // Inicializar tabla
            if(datatable_normasnacionales != null)
            {
                datatable_normasnacionales.destroy();
                datatable_normasnacionales = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_normasnacionales" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Entidades</th>' +
                                                '<th>Nombre norma/estándar</th>' +
                                                '<th>Descripción</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_normasnacionales(catalogo);
            break;
        case 6:
            
            // activa menu
            $("#titulo_tabla").html('Catálogo [Norma/estándar internacionales]');
            $("#tr_6").addClass("active");
            $("#cat_6").addClass("text-info");

            // Inicializar tabla
            if(datatable_normasinternacionales != null)
            {
                datatable_normasinternacionales.destroy();
                datatable_normasinternacionales = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_normasinternacionales" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Entidades</th>' +
                                                '<th>Nombre norma/estándar</th>' +
                                                '<th>Descripción</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_normasinternacionales(catalogo);
            break;
        case 7:
            // campos


            // activa menu
            $("#titulo_tabla").html('Catálogo [Tallas]');
            $("#tr_7").addClass("active");
            $("#cat_7").addClass("text-info");

            // Inicializar tabla
            if(datatable_tallas != null)
            {
                datatable_tallas.destroy();
                datatable_tallas = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_talla" width="100%">'+
                                         '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Talla</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_talla(catalogo);
            break;
        case 8:
        
            // menu
            $("#titulo_tabla").html('Catálogo [Clasificación del Riesgo]');
            $("#tr_8").addClass("active");
            $("#cat_8").addClass("text-info");

            // Inicializar tabla
            if(datatable_clasificacionriesgp != null)
            {
                datatable_clasificacionriesgp.destroy();
                datatable_clasificacionriesgp = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_clasificacionriesgo" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Clasificación del Riesgo</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');
            
            tabla_clasificacionriesgo(catalogo);
            break;
        case 9:
         
            $("#titulo_tabla").html('Catálogo [Tipo de uso]');
            $("#tr_9").addClass("active");
            $("#cat_9").addClass("text-info");

            // Inicializar tabla
            if(datatable_tipouso != null)
            {
                datatable_tipouso.destroy();
                datatable_tipouso = null;
            }

            // diseño tabla
                  // $("#div_datatable").html('<table class="table table-bordered table-hover" id="tabla_lista_tipouso" width="100%">'+
                              $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_tipouso" width="100%">'+

                                       '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Tipo de uso</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_tipouso(catalogo);
            break;
            case 11:
         
            $("#titulo_tabla").html('Catálogo [Entidades]');
            $("#tr_11").addClass("active");
            $("#cat_11").addClass("text-info");

            // Inicializar tabla
            if(datatable_entidades != null)
            {
                datatable_entidades.destroy();
                datatable_entidades = null;
            }

            // diseño tabla
                  // $("#div_datatable").html('<table class="table table-bordered table-hover" id="tabla_lista_tipouso" width="100%">'+
                              $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_entidades" width="100%">'+

                                       '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Entidad</th>' +
                                                '<th>Descripción</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_entidades(catalogo);
            break;

    }
}


$("#boton_nuevo_registro").click(function()
{
    switch (catalogo)
    {
        case 1:
            
            
                var selectTallas = $('#TALLAS_EPP').selectize({
                    placeholder: 'Seleccione una o varias opciones',
                    allowEmptyOption: true,
                    closeAfterSelect: false,
                })[0].selectize;
                     
                 var selectRiesgo = $('#CLASIFICACION_RIESGO_EPP').selectize({
                    placeholder: 'Seleccione una o varias opciones',
                    allowEmptyOption: true,
                    closeAfterSelect: false,
                })[0].selectize;
            
                $("#tab1_epp_info").click();
                $("#tab2_documentos_epp").hide();
                $("#tab2_documentos").hide();


            
                $("#DIV_TALLAS_EPP").hide();
                $("#DIV_DISCAPACIODAD").hide();
                $("#DIV_REQUIERE_PRUEBA").hide();
                $("#DIV_INSPECCION_INTERNA").hide();
                $("#DIV_INSPECCION_EXTERNA").hide();
                $("#DIV_DESCONTAMINACION").hide();
                $("#NOM_NACIONALES_BOTON").hide();
                $("#NOM_NACIONALES_DIV").hide();
                $("#NOM_INTERNACIONALES_BOTON").hide();
                $("#NOM_INTERNACIONALES_DIV").hide();
                $("#TABLA_FRECUENCIA").hide();
                $("#TABLA_ATENUACION").hide();
                $("#TABLA_DESVIACION").hide();
                $("#DIV_ATENUACION_RUIDO").hide();

                $('#form_epp').each(function(){
                    this.reset();
                });
    
                $("#ID_CAT_EPP").val(0);
                $("#catalogo").val(catalogo);
    
                if ($('#FOTO_EPP').data('dropify'))
                {
                    $('#FOTO_EPP').dropify().data('dropify').resetPreview();
                    $('#FOTO_EPP').dropify().data('dropify').clearElement();
                    
                }
                else
                {
                   
                    $('#FOTO_EPP').dropify({
                        messages: {
                            'default': 'Arrastre la imagen aquí o haga click',
                            'replace': 'Arrastre la imagen o haga clic para reemplazar',
                            'remove':  'Quitar',
                            'error':   'Ooops, ha ocurrido un error.'
                        },
                        error: {
                            'fileSize': 'Demasiado grande ({{ value }} max).',
                            'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                            'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                            'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                            'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                            'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                        }
                    });
                }

                selectTallas.clear();
                selectRiesgo.clear();
            
                $('#modal_epp').modal({ backdrop: false });
            
                $(".listacaracteristicasespecificas").empty();
                $(".materialesutilizadosfabricante").empty();
                $(".certificacionesadicionales").empty();
                $(".restriccionesdeuso").empty();
                $(".recomendacionalmacenamiento").empty();
                $(".recomendacionlimpieza").empty();
                $(".recomendaciondisposicionfinal").empty();
                $(".listanomnacionales").empty();
                $(".listanominternacionales").empty();

            break;
          case 2:
                $('#form_region_anatomica').each(function(){
                    this.reset();
                });
    
                $("#ID_REGION_ANATOMICA").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_regionanatomica').modal({backdrop:false});
           break;
        
          case 3:
                $('#form_claveyepp').each(function(){
                    this.reset();
                });
    
                $("#ID_CLAVE_EPP").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_claveyepp').modal({backdrop:false});
            break;
        
           case 4:
                $('#form_marcas').each(function(){
                    this.reset();
                });
    
                $("#ID_MARCAS_EPP").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_marcas').modal({backdrop:false});
            break;
          case 5:
                $('#form_normas_nacionales').each(function(){
                    this.reset();
                });
    
                $("#ID_NORMAS_NACIONALES").val(0);
                 $("#catalogo").val(catalogo);
            
                $('#modal_normasnacionales').modal({ backdrop: false });
            
                $(".notasnomnacionales").empty();
                $(".apartadonomnacionales").empty();
            break;
         case 6:
                $('#form_normas_internacionales').each(function(){
                    this.reset();
                });
    
                $("#ID_NORMAS_INTERNACIONALES").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_normasinternacionales').modal({ backdrop: false });
            
                $(".notasnominternacional").empty();
                $(".apartadonominternacional").empty();
            break;
         case 7:
                $('#form_tallas').each(function(){
                    this.reset();
                });
    
                $("#ID_TALLA").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_tallas').modal({backdrop:false});
            break;
        case 8:
                $('#form_clasificacionriesgo').each(function(){
                    this.reset();
                });
    
                $("#ID_CLASIFICACION_RIESGO").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_clasificacionriesgo').modal({backdrop:false});
            break;
         case 9:
                $('#form_tipouso').each(function(){
                    this.reset();
                });
    
                $("#ID_TIPO_USO").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_tipouso').modal({backdrop:false});
            break;
          case 11:
                $('#form_entidades').each(function(){
                    this.reset();
                });
    
                $("#ID_ENTIDAD_EPP").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_entidades').modal({backdrop:false});
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
// CATALOGO DE EPP GENERAL
//=======================================

document.getElementById('CLAVEYEPP_EPP').addEventListener('change', function () {
    let tipo = this.options[this.selectedIndex].getAttribute('data-tipo');
    document.getElementById('TIPO_RIESGO_EPP').value = tipo ? tipo : '';
});

document.getElementById('REGION_ANATOMICA_EPP').addEventListener('change', function () {
    const regionSeleccionada = this.value;
    const selectClave = document.getElementById('CLAVEYEPP_EPP');

    if (!selectClave.dataset.loaded) {
        selectClave.dataset.loaded = "true";
        selectClave.dataset.originalOptions = selectClave.innerHTML;
    }

    const todasLasOpciones = selectClave.dataset.originalOptions;

    const temp = document.createElement('div');
    temp.innerHTML = todasLasOpciones;

    const opciones = temp.querySelectorAll('option');

    selectClave.innerHTML = '<option value="">Seleccione una opción</option>';

    opciones.forEach(opt => {
        const region = opt.getAttribute('data-region');

        if (!regionSeleccionada) return;

        if (region === regionSeleccionada) {
            selectClave.appendChild(opt.cloneNode(true));
        }
    });

});

///// DINAMICO CARACTERISTICAS ESPECIFICAS
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregar = document.getElementById('botonagregarcaracteristicasespecificas');
    botonAgregar.addEventListener('click', agregarCaracteristicas);

    function agregarCaracteristicas() {

        const contenedor = document.querySelector('.listacaracteristicasespecificas');

        let ultimaFila = contenedor.querySelector('.fila-caracteristicas:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-caracteristicas', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Características específicas *</label>
                <input type="text" class="form-control" name="CARACTERISTICA_EPP" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarCaracteristicas">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarCaracteristicas')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

///// DINAMICO MATERIALES UTILIZADOS
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregar = document.getElementById('botonagregarmaterialesutilizados');
    botonAgregar.addEventListener('click', agregarMaterial);

    function agregarMaterial() {

        const contenedor = document.querySelector('.materialesutilizadosfabricante');

        let ultimaFila = contenedor.querySelector('.fila-materiales:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-4').length === 3) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-materiales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-4');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Nombre del material *</label>
                <input type="text" class="form-control" name="MATERIAL_UTILIZADO" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarMaterial">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarMaterial')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-4').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

///// DINAMICO CERTIFICACIONES ADICIONALES
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarcertficaciones = document.getElementById('botonagregarcertificacionesadicionales');
    botonAgregarcertficaciones.addEventListener('click', agregarcertificaciones);

    function agregarcertificaciones() {

        const contenedor = document.querySelector('.certificacionesadicionales');

        let ultimaFila = contenedor.querySelector('.fila-certificaciones:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-certificaciones', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Certificaciones adicionales *</label>
                <input type="text" class="form-control" name="CERTIFICACIONES_ADICIONALES" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarcertificaciones">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarcertificaciones')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

///// DINAMICO RESTRICIONES DE USO
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarestriccionuso = document.getElementById('botonagregarestriccionesuso');
    botonAgregarestriccionuso.addEventListener('click', agregarestriccionesuso);

    function agregarestriccionesuso() {

        const contenedor = document.querySelector('.restriccionesdeuso');

        let ultimaFila = contenedor.querySelector('.fila-restricciones:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-restricciones', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Restricciones de uso *</label>
                <input type="text" class="form-control" name="RESTRICCIONES_USO" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarestricciones">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarestricciones')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

///// DINAMICO RECOMENDACION ALMACENAMIENTO
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarecomendacionesalmacenamiento = document.getElementById('botonagregarecomendacionalmacenamiento');
    botonAgregarecomendacionesalmacenamiento.addEventListener('click', agregarecomendacionaesalamcenamiento);

    function agregarecomendacionaesalamcenamiento() {

        const contenedor = document.querySelector('.recomendacionalmacenamiento');

        let ultimaFila = contenedor.querySelector('.fila-recoalmacenamiento:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-recoalmacenamiento', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Recomendación *</label>
                <input type="text" class="form-control" name="RECOMENDACION_ALMACENAMIENTO" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarrecomendacionalmacenamietno">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarrecomendacionalmacenamietno')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

///// DINAMICO RECOMENDACION LIMPIEZA  
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarecolimpieza = document.getElementById('botonagregarecomendacionlimpieza');
    botonAgregarecolimpieza.addEventListener('click', agregarecolimpieza);

    function agregarecolimpieza() {

        const contenedor = document.querySelector('.recomendacionlimpieza');

        let ultimaFila = contenedor.querySelector('.fila-recolimpieza:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-recolimpieza', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Recomendación *</label>
                <input type="text" class="form-control" name="RECOMENDACION_LIMPIEZA" required>
                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarecolimpieza">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarecolimpieza')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

///// DINAMICO RECOMENDACION DISPOSICION FINAL  
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarecodisposicion = document.getElementById('botonagregarecomendaciondisposicionfinal');
    botonAgregarecodisposicion.addEventListener('click', agregarecodisposicion);

    function agregarecodisposicion() {

        const contenedor = document.querySelector('.recomendaciondisposicionfinal');

        let ultimaFila = contenedor.querySelector('.fila-recodisposicion:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-recodisposicion', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Recomendación *</label>
                <input type="text" class="form-control" name="RECOMENDACION_DISPOSICION" required>
                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarecodisposicion">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarecodisposicion')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const selectTallas = document.getElementById('FABRICATALLAS_EPP');
    const divTallas = document.getElementById('DIV_TALLAS_EPP');

    selectTallas.addEventListener('change', function () {
        if (this.value === "1") {
            divTallas.style.display = "block";
        } else {
            divTallas.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const selectDiscapacidad = document.getElementById('TRABAJADORES_DISCAPACIDAD_EPP');
    const divDiscapacidad = document.getElementById('DIV_DISCAPACIODAD');

    selectDiscapacidad.addEventListener('change', function () {
        if (this.value === "1") {
            divDiscapacidad.style.display = "block";
        } else {
            divDiscapacidad.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const selectRequiere = document.getElementById('REQUIERE_AJUSTE_EPP');
    const divRequierePrueba = document.getElementById('DIV_REQUIERE_PRUEBA');

    selectRequiere.addEventListener('change', function () {
        if (this.value === "1") {
            divRequierePrueba.style.display = "block";
        } else {
            divRequierePrueba.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const selectInspeccion = document.getElementById('INSPECCION_INTERNA_EPP');
    const divInspeccion = document.getElementById('DIV_INSPECCION_INTERNA');

    selectInspeccion.addEventListener('change', function () {
        if (this.value === "1") {
            divInspeccion.style.display = "block";
        } else {
            divInspeccion.style.display = "none";
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const selectInspeccionExterna = document.getElementById('INSPECCION_EXTERNA_EPP');
    const divInspeccionExterna = document.getElementById('DIV_INSPECCION_EXTERNA');

    selectInspeccionExterna.addEventListener('change', function () {
        if (this.value === "1") {
            divInspeccionExterna.style.display = "block";
        } else {
            divInspeccionExterna.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {

    const selectDescontaminacion = document.getElementById('PROCEDIMIENTO_DESCONTAMINACION_EPP');
    const divDescontaminacion = document.getElementById('DIV_DESCONTAMINACION');

    selectDescontaminacion.addEventListener('change', function () {
        if (this.value === "1") {
            divDescontaminacion.style.display = "block";
        } else {
            divDescontaminacion.style.display = "none";
        }
    });

});

document.addEventListener("DOMContentLoaded", function () {

    const selectNacionales = document.getElementById("CUMPLE_NORMA_NACIONALES");
    const divBoton = document.getElementById("NOM_NACIONALES_BOTON");
    const divContenido = document.getElementById("NOM_NACIONALES_DIV");

    selectNacionales.addEventListener("change", function () {

        if (this.value === "1") { 
            divBoton.style.display = "block";
            divContenido.style.display = "block";
        } else {
            divBoton.style.display = "none";
            divContenido.style.display = "none";
        }

    });

});

document.addEventListener("DOMContentLoaded", function () {

    const selectInternacionales = document.getElementById("CUMPLE_NORMA_INTERNACIONALES");
    const divBotonIntl = document.getElementById("NOM_INTERNACIONALES_BOTON");
    const divContenidoIntl = document.getElementById("NOM_INTERNACIONALES_DIV");

    selectInternacionales.addEventListener("change", function () {

        if (this.value === "1") { 
            divBotonIntl.style.display = "block";
            divContenidoIntl.style.display = "block";
        } else {
            divBotonIntl.style.display = "none";
            divContenidoIntl.style.display = "none";
        }

    });

});
    
////// DINAMICO NOM NACIONALES
document.addEventListener("DOMContentLoaded", function() {

    const botonAgregarnomnacionales = document.getElementById('botonagregarnomnacionales');
    botonAgregarnomnacionales.addEventListener('click', agregarnomnacionales);

    function agregarnomnacionales() {

        let opciones = `<option value="">Seleccione una norma</option>`;
        window.catnormasnacionales.forEach(function(norma) {
            opciones += `
                <option value="${norma.ID_NORMAS_NACIONALES}">
                    ${norma.NOMBRE_NORMA_NACIONALES}
                </option>`;
        });

        const divnomnacionales = document.createElement('div');
        divnomnacionales.classList.add('row', 'generarnomnacionales', 'mb-3');

        divnomnacionales.innerHTML = `
        <div class="col-6">
            <div class="form-group">
                <label>Norma/estándar nacional *</label>
                <select class="form-control select-norma-nacional" name="NOM_NACIONALES" required>
                    ${opciones}
                </select>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label>Apartado específico *</label>
                <select class="form-control select-apartado-nacional" name="APARTADO_NACIONALES" required>
                    <option value="">Seleccione una norma primero</option>
                </select>
            </div>
        </div>

        <div class="col-12">
            <div class="form-group" style="text-align: center;">
                <button type="button" class="btn btn-danger botonEliminarNomnacionales">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        `;

        const contenedor = document.querySelector('.listanomnacionales');
        contenedor.appendChild(divnomnacionales);

        const botonEliminar = divnomnacionales.querySelector('.botonEliminarNomnacionales');
        botonEliminar.addEventListener('click', function() {
            contenedor.removeChild(divnomnacionales);
        });

        const selectNorma = divnomnacionales.querySelector('.select-norma-nacional');
        const selectApartado = divnomnacionales.querySelector('.select-apartado-nacional');

        selectNorma.addEventListener('change', function () {

            const idNorma = this.value;
            const norma = window.catnormasnacionales.find(n => n.ID_NORMAS_NACIONALES == idNorma);

            selectApartado.innerHTML = ""; 

            if (!norma) {
                selectApartado.innerHTML = `<option value="">Error al cargar norma</option>`;
                return;
            }

           
            let apartados = norma.APARTADO_NACIONALES_JSON;

            if (typeof apartados === "string") {
                try {
                    apartados = JSON.parse(apartados);
                } catch (e) {
                    apartados = [];
                }
            }

            if (!Array.isArray(apartados)) {
                apartados = [];
            }

            if (apartados.length === 0) {
                selectApartado.innerHTML = `
                    <option value="">Esta norma no tiene apartados registrados</option>
                `;
                return;
            }

         
            let opcionesApartados = `<option value="">Seleccione un apartado</option>`;

            apartados.forEach(function(item) {
                opcionesApartados += `
                    <option value="${item.APARTADO_ESPECIFICO_NACIONALES}">
                        ${item.APARTADO_ESPECIFICO_NACIONALES}
                    </option>`;
            });

            selectApartado.innerHTML = opcionesApartados;
        });
    }
});

////// DINAMICO NOM INTERNACIONALES
document.addEventListener("DOMContentLoaded", function() {

    const botonAgregarnominternacionales = document.getElementById('botonagregarnominternacionales');
    botonAgregarnominternacionales.addEventListener('click', agregarnominternacionales);

    function agregarnominternacionales() {

        let opciones = `<option value="">Seleccione una norma</option>`;
        window.catnormasinternacionales.forEach(function(norma) {
            opciones += `
                <option value="${norma.ID_NORMAS_INTERNACIONALES}">
                    ${norma.NOMBRE_NORMA_INTERNACIONALES}
                </option>`;
        });

        const divnominternacional = document.createElement('div');
        divnominternacional.classList.add('row', 'generarnominternacional', 'mb-3');

        divnominternacional.innerHTML = `
        <div class="col-6">
            <div class="form-group">
                <label>Norma/estándar internacional *</label>
                <select class="form-control select-norma-internacional" name="NOM_INTERNACIONALES" required>
                    ${opciones}
                </select>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label>Apartado específico *</label>
                <select class="form-control select-apartado-internacional" name="APARTADO_INTERNACIONALES" required>
                    <option value="">Seleccione una norma primero</option>
                </select>
            </div>
        </div>

        <div class="col-12">
            <div class="form-group" style="text-align: center;">
                <button type="button" class="btn btn-danger botonEliminarNominternacionales">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        `;

        const contenedor = document.querySelector('.listanominternacionales');
        contenedor.appendChild(divnominternacional);

        const botonEliminar = divnominternacional.querySelector('.botonEliminarNominternacionales');
        botonEliminar.addEventListener('click', function() {
            contenedor.removeChild(divnominternacional);
        });

        const selectNorma = divnominternacional.querySelector('.select-norma-internacional');
        const selectApartado = divnominternacional.querySelector('.select-apartado-internacional');

        selectNorma.addEventListener('change', function () {

            const idNorma = this.value;
            const norma = window.catnormasinternacionales.find(n => n.ID_NORMAS_INTERNACIONALES == idNorma);

            selectApartado.innerHTML = ""; 

            if (!norma) {
                selectApartado.innerHTML = `<option value="">Error al cargar norma</option>`;
                return;
            }

           
            let apartados = norma.APARTADO_INTERNACIONALES_JSON;

            if (typeof apartados === "string") {
                try {
                    apartados = JSON.parse(apartados);
                } catch (e) {
                    apartados = [];
                }
            }

            if (!Array.isArray(apartados)) {
                apartados = [];
            }

            if (apartados.length === 0) {
                selectApartado.innerHTML = `
                    <option value="">Esta norma no tiene apartados registrados</option>
                `;
                return;
            }

         
            let opcionesApartados = `<option value="">Seleccione un apartado</option>`;

            apartados.forEach(function(item) {
                opcionesApartados += `
                    <option value="${item.APARTADO_ESPECIFICO_INTERNACIONALES}">
                        ${item.APARTADO_ESPECIFICO_INTERNACIONALES}
                    </option>`;
            });

            selectApartado.innerHTML = opcionesApartados;
        });
    }
});


/// DIV CUANDO SEA OIDO

document.getElementById("REGION_ANATOMICA_EPP").addEventListener("change", function () {

    const valor = this.value;

    const divs = [
        "TABLA_FRECUENCIA",
        "TABLA_ATENUACION",
        "TABLA_DESVIACION",
        "DIV_ATENUACION_RUIDO"
    ];

    if (valor == "3") {

        divs.forEach(id => {
            document.getElementById(id).style.display = "block";
        });

    } else {

        divs.forEach(id => {

            const div = document.getElementById(id);

            div.style.display = "none";

            const inputs = div.querySelectorAll("input, textarea, select");

            inputs.forEach(input => {
                input.value = "";
            });
        });
    }
});



$("#boton_guardar_epp").click(function (e) {
    e.preventDefault();

    var form = document.getElementById("form_epp");

    // VALIDACIÓN HTML5
    if (!form.checkValidity()) {
        form.reportValidity(); 
        return; 
    }

    var formData = new FormData($('#form_epp')[0]);

    var caracteristicasespecificos = [];
    $("[name='CARACTERISTICA_EPP']").each(function () {
        caracteristicasespecificos.push({ 'CARACTERISTICA_EPP': $(this).val() });
    });
    formData.append('CARACTERISTICAS_ESPECIFICAS_EPP', JSON.stringify(caracteristicasespecificos));


    var materialesutilizados = [];
    $("[name='MATERIAL_UTILIZADO']").each(function () {
        materialesutilizados.push({ 'MATERIAL_UTILIZADO': $(this).val() });
    });
    formData.append('MATERIALES_UTILIZADOS_EPP', JSON.stringify(materialesutilizados));


    var certificacionesadicionales = [];
    $("[name='CERTIFICACIONES_ADICIONALES']").each(function () {
        certificacionesadicionales.push({ 'CERTIFICACIONES_ADICIONALES': $(this).val() });
    });
    formData.append('CERTIFICACIONES_ADICIONALES_EPP', JSON.stringify(certificacionesadicionales));


    var restriccionesdeuso = [];
    $("[name='RESTRICCIONES_USO']").each(function () {
        restriccionesdeuso.push({ 'RESTRICCIONES_USO': $(this).val() });
    });
    formData.append('RESTRICCIONES_USO_EPP', JSON.stringify(restriccionesdeuso));


    var recomendacionalmacenamiento = [];
    $("[name='RECOMENDACION_ALMACENAMIENTO']").each(function () {
        recomendacionalmacenamiento.push({ 'RECOMENDACION_ALMACENAMIENTO': $(this).val() });
    });
    formData.append('RECOMENDACION_ALMACENAMIENTO_EPP', JSON.stringify(recomendacionalmacenamiento));


    var recomendacionlimpieza = [];
    $("[name='RECOMENDACION_LIMPIEZA']").each(function () {
        recomendacionlimpieza.push({ 'RECOMENDACION_LIMPIEZA': $(this).val() });
    });
    formData.append('RECOMENDACION_LIMPIEZA_EPPS', JSON.stringify(recomendacionlimpieza));


    var recomendaciondisposicion = [];
    $("[name='RECOMENDACION_DISPOSICION']").each(function () {
        recomendaciondisposicion.push({ 'RECOMENDACION_DISPOSICION': $(this).val() });
    });
    formData.append('RECOMENDACION_DISPOSICION_EPPS', JSON.stringify(recomendaciondisposicion));


    var nomnacionales = [];
        $(".generarnomnacionales").each(function() {
            var inputnomnacionales = {
                'NOM_NACIONALES': $(this).find("select[name='NOM_NACIONALES']").val(),
                'APARTADO_NACIONALES': $(this).find("select[name='APARTADO_NACIONALES']").val(),
                

            };
            nomnacionales.push(inputnomnacionales);
        });

    formData.append('NORMASNACIONALES_EPP', JSON.stringify(nomnacionales));


      var nominternacionales = [];
        $(".generarnominternacional ").each(function() {
            var inputnominternacionales = {
                'NOM_INTERNACIONALES': $(this).find("select[name='NOM_INTERNACIONALES']").val(),
                'APARTADO_INTERNACIONALES': $(this).find("select[name='APARTADO_INTERNACIONALES']").val(),
                

            };
            nominternacionales.push(inputnominternacionales);
        });

    formData.append('NORMASINTERNACIONALES_EPP', JSON.stringify(nominternacionales));



    $.ajax({
        type: 'POST',
        url: "/eppcatalogos",
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,

        beforeSend: function () {
            $('#boton_guardar_epp').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
        },

        success: function (dato) {
            tabla_epp(catalogo);

            swal({
                title: "Correcto",
                text: "Información guardada correctamente",
                type: "success",
                buttons: { visible: false },
                timer: 1500
            });

            $('#boton_guardar_epp').html('Guardar <i class="fa fa-save"></i>');
            $('#modal_epp').modal('hide');
        },

        error: function (dato) {
            $('#boton_guardar_epp').html('Guardar <i class="fa fa-save"></i>');

            swal({
                title: "Error",
                text: "Error al guardar la información",
                type: "error",
                buttons: { visible: false },
                timer: 1500
            });
        }
    });
});


function tabla_epp(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_epp != null)
        {
            datatable_epp.clear().draw();
            datatable_epp.ajax.url(ruta).load();
        }
        else
        {
            datatable_epp = $('#tabla_lista_epp').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_epp(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                      {
                        "data": "FOTO_EPP_TABLA"
                    },
                    {
                        "data": "TEXTO_REGION_EPP"
                    },
                     {
                        "data": "TEXTO_CLAVEYEPP_EPP"
                    },
                    {
                        "data": "NOMBRE_EPP"
                    },
                    {
                        "data": "MODELO_EPP"
                    },
                    {
                        "data": "TEXTO_MARCA_EPP"
                    },
                    {
                        "data": "TEXTO_CLASIFICACION_RIESGO_EPP"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[0, "asc"]],  
                rowsGroup: [1],
                "searching": true,
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
        tabla_epp(num_catalogo);
    }    
}

function editar_epp()
{
    $('#tabla_lista_epp tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_epp.row(tr);

        $('#form_epp').each(function(){
            this.reset();
        });

        $("#tab1_epp_info").click();
        $("#tab2_documentos_epp").show();
        
        $("#ID_CAT_EPP").val(row.data().ID_CAT_EPP);

        CAT_EPP_ID = row.data().ID_CAT_EPP

        $("#REGION_ANATOMICA_EPP").val(row.data().REGION_ANATOMICA_EPP);
        $("#CLAVEYEPP_EPP").val(row.data().CLAVEYEPP_EPP);
        $("#TIPO_RIESGO_EPP").val(row.data().TIPO_RIESGO_EPP);
        $("#NOMBRE_EPP").val(row.data().NOMBRE_EPP);
        $("#MARCA_EPP").val(row.data().MARCA_EPP);
        $("#MODELO_EPP").val(row.data().MODELO_EPP);
        $("#TIPO_GRADO_EPP").val(row.data().TIPO_GRADO_EPP);
        $("#FABRICATALLAS_EPP").val(row.data().FABRICATALLAS_EPP);
        $("#RECOMENDACIONES_TALLAS_EPP").val(row.data().RECOMENDACIONES_TALLAS_EPP);
        $("#TRABAJADORES_DISCAPACIDAD_EPP").val(row.data().TRABAJADORES_DISCAPACIDAD_EPP);
        $("#ESPECIFIQUE_FORMA_EPP").val(row.data().ESPECIFIQUE_FORMA_EPP);
        $("#CUAL_CLASIFICACION_EPP").val(row.data().CUAL_CLASIFICACION_EPP);
        $("#TIPO_USO_EPP").val(row.data().TIPO_USO_EPP);
        $("#PARTE_EXPUESTA_EPP").val(row.data().PARTE_EXPUESTA_EPP);
        $("#RECOMENDACIONES_USO_EPP").val(row.data().RECOMENDACIONES_USO_EPP);
        $("#REQUIERE_AJUSTE_EPP").val(row.data().REQUIERE_AJUSTE_EPP);
        $("#ESPECIFIQUE_AJUSTE_EPP").val(row.data().ESPECIFIQUE_AJUSTE_EPP);
        $("#UTILIZAR_EMERGENCIA_EPP").val(row.data().UTILIZAR_EMERGENCIA_EPP);
        $("#ESPECIFIQUE_EMERGENCIA_EPP").val(row.data().ESPECIFIQUE_EMERGENCIA_EPP);
        $("#COMPATIBILIDAD_EPPS").val(row.data().COMPATIBILIDAD_EPPS);
        $("#INCOMPATIBILIDAD_EPPS").val(row.data().INCOMPATIBILIDAD_EPPS);
        $("#INSPECCION_INTERNA_EPP").val(row.data().INSPECCION_INTERNA_EPP);
        $("#FRECUENCIA_INTERNA_EPP").val(row.data().FRECUENCIA_INTERNA_EPP);
        $("#RESPONSABLE_INTERNA_EPP").val(row.data().RESPONSABLE_INTERNA_EPP);
        $("#INSPECCION_EXTERNA_EPP").val(row.data().INSPECCION_EXTERNA_EPP);
        $("#FRECUENCIA_EXTERNA_EPP").val(row.data().FRECUENCIA_EXTERNA_EPP);
        $("#RESPONSABLE_EXTERNA_EPP").val(row.data().RESPONSABLE_EXTERNA_EPP);
        $("#PROCEDIMIENTO_DESCONTAMINACION_EPP").val(row.data().PROCEDIMIENTO_DESCONTAMINACION_EPP);
        $("#DESCONTAMINACION_ESPECIFIQUE_EPP").val(row.data().DESCONTAMINACION_ESPECIFIQUE_EPP);
        $("#VIDA_UTIL_EPP").val(row.data().VIDA_UTIL_EPP);
        $("#CRITERIOS_DESECHAR_EPP").val(row.data().CRITERIOS_DESECHAR_EPP);
        $("#CUMPLE_NORMA_NACIONALES").val(row.data().CUMPLE_NORMA_NACIONALES);
        $("#CUMPLE_NORMA_INTERNACIONALES").val(row.data().CUMPLE_NORMA_INTERNACIONALES);

        $("#ATENUACION_125").val(row.data().ATENUACION_125);
        $("#ATENUACION_250").val(row.data().ATENUACION_250);
        $("#ATENUACION_500").val(row.data().ATENUACION_500);
        $("#ATENUACION_1000").val(row.data().ATENUACION_1000);
        $("#ATENUACION_2000").val(row.data().ATENUACION_2000);
        $("#ATENUACION_3150").val(row.data().ATENUACION_3150);
        $("#ATENUACION_4000").val(row.data().ATENUACION_4000);
        $("#ATENUACION_6300").val(row.data().ATENUACION_6300);
        $("#ATENUACION_8000").val(row.data().ATENUACION_8000);
        $("#DESVIACION_125").val(row.data().DESVIACION_125);
        $("#DESVIACION_250").val(row.data().DESVIACION_250);
        $("#DESVIACION_500").val(row.data().DESVIACION_500);
        $("#DESVIACION_1000").val(row.data().DESVIACION_1000);
        $("#DESVIACION_2000").val(row.data().DESVIACION_2000);
        $("#DESVIACION_3150").val(row.data().DESVIACION_3150);
        $("#DESVIACION_4000").val(row.data().DESVIACION_4000);
        $("#DESVIACION_6300").val(row.data().DESVIACION_6300);
        $("#DESVIACION_8000").val(row.data().DESVIACION_8000);
        $("#ATENUACION_H").val(row.data().ATENUACION_H);
        $("#ATENUACION_M").val(row.data().ATENUACION_M);
        $("#ATENUACION_L").val(row.data().ATENUACION_L);
        $("#VALOR_SNR").val(row.data().VALOR_SNR);
        $("#VALOR_NRR").val(row.data().VALOR_NRR);

        filtrarClaveAlEditar(row);

        if (!$('#TALLAS_EPP')[0].selectize) {
            $('#TALLAS_EPP').selectize({
                placeholder: 'Seleccione una o varias opciones',
                maxItems: null
            });
        }

        let selTallas = $('#TALLAS_EPP')[0].selectize;
        selTallas.clear();

        let valoresTallas = row.data().TALLAS_EPP;

        if (typeof valoresTallas === "string") {
            try { valoresTallas = JSON.parse(valoresTallas); } catch (e) { valoresTallas = []; }
        }

        if (Array.isArray(valoresTallas)) {
            selTallas.setValue(valoresTallas);
        }


        if (!$('#CLASIFICACION_RIESGO_EPP')[0].selectize) {
            $('#CLASIFICACION_RIESGO_EPP').selectize({
                placeholder: 'Seleccione una o varias opciones',
                maxItems: null
            });
        }

        let selRiesgo = $('#CLASIFICACION_RIESGO_EPP')[0].selectize;
        selRiesgo.clear();

        let valoresRiesgo = row.data().CLASIFICACION_RIESGO_EPP;

        if (typeof valoresRiesgo === "string") {
            try { valoresRiesgo = JSON.parse(valoresRiesgo); } catch (e) { valoresRiesgo = []; }
        }

        if (Array.isArray(valoresRiesgo)) {
            selRiesgo.setValue(valoresRiesgo);
        }

    
        if (row.data().FABRICATALLAS_EPP == "1") {
            $('#DIV_TALLAS_EPP').show();
        } else {
            $('#DIV_TALLAS_EPP').hide();
        }

        if (row.data().TRABAJADORES_DISCAPACIDAD_EPP == "1") {
            $('#DIV_DISCAPACIODAD').show();
        } else {
            $('#DIV_DISCAPACIODAD').hide();
        }

        if (row.data().REQUIERE_AJUSTE_EPP == "1") {
            $('#DIV_REQUIERE_PRUEBA').show();
        } else {
            $('#DIV_REQUIERE_PRUEBA').hide();
        }

        if (row.data().INSPECCION_INTERNA_EPP == "1") {
            $('#DIV_INSPECCION_INTERNA').show();
        } else {
            $('#DIV_INSPECCION_INTERNA').hide();
        }

        if (row.data().INSPECCION_EXTERNA_EPP == "1") {
            $('#DIV_INSPECCION_EXTERNA').show();
        } else {
            $('#DIV_INSPECCION_EXTERNA').hide();
        }

        if (row.data().PROCEDIMIENTO_DESCONTAMINACION_EPP == "1") {
            $('#DIV_DESCONTAMINACION').show();
        } else {
            $('#DIV_DESCONTAMINACION').hide();
        }


        if (row.data().TRABAJADORES_DISCAPACIDAD_EPP == "1") {
            $('#DIV_DISCAPACIODAD').show();
        } else {
            $('#DIV_DISCAPACIODAD').hide();
        }



        if (row.data().CUMPLE_NORMA_NACIONALES == "1") {
            $('#NOM_NACIONALES_BOTON').show();
            $('#NOM_NACIONALES_DIV').show();
              
        } else {
            $('#NOM_NACIONALES_BOTON').hide();
            $('#NOM_NACIONALES_DIV').hide();

        }


        if (row.data().CUMPLE_NORMA_INTERNACIONALES == "1") {
            $('#NOM_INTERNACIONALES_BOTON').show();
            $('#NOM_INTERNACIONALES_DIV').show();
              
        } else {
            $('#NOM_INTERNACIONALES_BOTON').hide();
            $('#NOM_INTERNACIONALES_DIV').hide();

        }

        if (row.data().REGION_ANATOMICA_EPP == "3") {

            $('#TABLA_FRECUENCIA').show();
            $('#TABLA_ATENUACION').show();
            $('#TABLA_DESVIACION').show();
            $('#DIV_ATENUACION_RUIDO').show();

        } else {

            $('#TABLA_FRECUENCIA').hide();
            $('#TABLA_ATENUACION').hide();
            $('#TABLA_DESVIACION').hide();
            $('#DIV_ATENUACION_RUIDO').hide();
        }




       if (row.data().FOTO_EPP) {
        var archivo = row.data().FOTO_EPP;
        var extension = archivo.substring(archivo.lastIndexOf("."));
        var imagenUrl = '/vereppfoto/' + row.data().ID_CAT_EPP + extension;

        if ($('#FOTO_EPP').data('dropify')) {
            $('#FOTO_EPP').dropify().data('dropify').destroy();
            $('#FOTO_EPP').dropify().data('dropify').settings.defaultFile = imagenUrl;
            $('#FOTO_EPP').dropify().data('dropify').init();
        } else {
            $('#FOTO_EPP').attr('data-default-file', imagenUrl);
            $('#FOTO_EPP').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove': 'Quitar',
                    'error': 'Ooops, ha ocurrido un error.'
                },
                error: {
                    'fileSize': 'Demasiado grande ({{ value }} max).',
                    'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                    'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                    'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                    'maxHeight': 'Alto demasiado grande (max {{ value }}px).',
                    'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                }
            });
        }
    } else {
        $('#FOTO_EPP').dropify().data('dropify').resetPreview();
        $('#FOTO_EPP').dropify().data('dropify').clearElement();
    }


        $("#catalogo").val(catalogo);
        $('#modal_epp').modal({ backdrop: false });
        
        $(".listacaracteristicasespecificas").empty();
        mostrarCaracteristicasEspecificas(row);

        $(".materialesutilizadosfabricante").empty();
        mostrarMaterialesUtilizados(row);

        $(".certificacionesadicionales").empty();
        mostrarCertificacionesadicionales(row);

         $(".restriccionesdeuso").empty();
        mostrarRestricciones(row);

         $(".recomendacionalmacenamiento").empty();
        mostrarRecomendacionesalmacenamiento(row);

        $(".recomendacionlimpieza").empty();
        mostrarRecomendacioneslimpieza(row);

         $(".recomendaciondisposicionfinal").empty();
        mostrarRecomendacionesdisposicion(row);

        $(".listanomnacionales").empty();
        mostrarNormasNacionales(row);

        $(".listanominternacionales").empty();
        mostrarNormasInternacionales(row);


        
         $("#tab1_epp_info").off("click").on("click", function () {
            $("#tab2_documentos").hide();
         });
        

        
        
        $("#tab2_documentos_epp").off("click").on("click", function () {
            $("#tab2_documentos").show();
            tabla_epp_documentos();

        });
        

        
    });
}

function mostrarCaracteristicasEspecificas(row) {

    let contenedor = document.querySelector('.listacaracteristicasespecificas');

    contenedor.innerHTML = "";

    let data = row.data().CARACTERISTICAS_ESPECIFICAS_EPP;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-caracteristicas', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Características específicas *</label>
                <input type="text" class="form-control" name="CARACTERISTICA_EPP"
                    value="${item.CARACTERISTICA_EPP}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarCaracteristicas">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarCaracteristicas')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarMaterialesUtilizados(row) {

    let contenedor = document.querySelector('.materialesutilizadosfabricante');

    contenedor.innerHTML = "";

    let data = row.data().MATERIALES_UTILIZADOS_EPP;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-4').length === 3) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-materiales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-4');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Nombre del material *</label>
                <input type="text" class="form-control" 
                       name="MATERIAL_UTILIZADO" 
                       value="${item.MATERIAL_UTILIZADO}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarMaterial">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarMaterial')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-4').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarCertificacionesadicionales(row) {

    let contenedor = document.querySelector('.certificacionesadicionales');

    contenedor.innerHTML = "";

    let data = row.data().CERTIFICACIONES_ADICIONALES_EPP;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-certificaciones', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Certificaciones adicionales *</label>
                <input type="text" class="form-control" 
                       name="CERTIFICACIONES_ADICIONALES" 
                       value="${item.CERTIFICACIONES_ADICIONALES}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarcertificaciones">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarcertificaciones')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarRestricciones(row) {

    let contenedor = document.querySelector('.restriccionesdeuso');

    contenedor.innerHTML = "";

    let data = row.data().RESTRICCIONES_USO_EPP;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-restricciones', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Restricción *</label>
                <input type="text" class="form-control" 
                       name="RESTRICCIONES_USO" 
                       value="${item.RESTRICCIONES_USO}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarestricciones">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarestricciones')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarRecomendacionesalmacenamiento(row) {

    let contenedor = document.querySelector('.recomendacionalmacenamiento');

    contenedor.innerHTML = "";

    let data = row.data().RECOMENDACION_ALMACENAMIENTO_EPP;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-recoalmacenamiento', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Recomendación *</label>
                <input type="text" class="form-control" 
                       name="RECOMENDACION_ALMACENAMIENTO" 
                       value="${item.RECOMENDACION_ALMACENAMIENTO}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarrecomendacionalmacenamietno">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarrecomendacionalmacenamietno')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarRecomendacioneslimpieza(row) {

    let contenedor = document.querySelector('.recomendacionlimpieza');

    contenedor.innerHTML = "";

    let data = row.data().RECOMENDACION_LIMPIEZA_EPPS;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-recolimpieza', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Recomendación *</label>
                <input type="text" class="form-control" 
                       name="RECOMENDACION_LIMPIEZA" 
                       value="${item.RECOMENDACION_LIMPIEZA}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarecolimpieza">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarecolimpieza')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarRecomendacionesdisposicion(row) {

    let contenedor = document.querySelector('.recomendaciondisposicionfinal');

    contenedor.innerHTML = "";

    let data = row.data().RECOMENDACION_DISPOSICION_EPPS;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-recodisposicion', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Recomendación *</label>
                <input type="text" class="form-control" 
                       name="RECOMENDACION_DISPOSICION" 
                       value="${item.RECOMENDACION_DISPOSICION}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarecodisposicion">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarecodisposicion')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}
function filtrarClaveAlEditar(row) {

    const region = row.data().REGION_ANATOMICA_EPP;
    const claveGuardada = row.data().CLAVEYEPP_EPP;

    const selectRegion = document.getElementById('REGION_ANATOMICA_EPP');
    const selectClave = document.getElementById('CLAVEYEPP_EPP');

    selectRegion.value = region;

    if (!selectClave.dataset.loaded) {
        selectClave.dataset.loaded = "true";
        selectClave.dataset.originalOptions = selectClave.innerHTML;
    }

    const todasLasOpciones = selectClave.dataset.originalOptions;

    const temp = document.createElement('div');
    temp.innerHTML = todasLasOpciones;

    const opciones = temp.querySelectorAll('option');

    selectClave.innerHTML = '<option value="">Seleccione una opción</option>';

    opciones.forEach(opt => {
        const regionOpcion = opt.getAttribute('data-region');

        if (regionOpcion === region) {
            selectClave.appendChild(opt.cloneNode(true));
        }
    });

    if (claveGuardada) {
        selectClave.value = claveGuardada;
    }
}

function mostrarNormasNacionales(row) {

    const contenedor = document.querySelector('.listanomnacionales');
    contenedor.innerHTML = ""; 


    let data = row.data().NORMASNACIONALES_EPP;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

 
    data.forEach((item, index) => {

        let normaSeleccionada = item.NOM_NACIONALES;
        let apartadoSeleccionado = item.APARTADO_NACIONALES;

        const fila = document.createElement('div');
        fila.classList.add('row', 'generarnomnacionales', 'mb-3');

        let opcionesNormas = `<option value="">Seleccione una norma</option>`;

        window.catnormasnacionales.forEach(norma => {
            opcionesNormas += `
                <option value="${norma.ID_NORMAS_NACIONALES}" 
                    ${norma.ID_NORMAS_NACIONALES == normaSeleccionada ? "selected" : ""}>
                    ${norma.NOMBRE_NORMA_NACIONALES}
                </option>`;
        });

        fila.innerHTML = `
            <div class="col-6">
                <div class="form-group">
                    <label>Norma/estándar nacional *</label>
                    <select class="form-control select-norma-nacional" 
                        name="NOM_NACIONALES" required>
                        ${opcionesNormas}
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label>Apartado específico *</label>
                    <select class="form-control select-apartado-nacional"
                        name="APARTADO_NACIONALES" required>
                    </select>
                </div>
            </div>

            <div class="col-12 text-center">
                <button type="button" class="btn btn-danger botonEliminarNomnacionales">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;

        contenedor.appendChild(fila);

    
        const selectNorma = fila.querySelector('.select-norma-nacional');
        const selectApartado = fila.querySelector('.select-apartado-nacional');

        function cargarApartados(idNorma, apartadoPreseleccionado = "") {

            const norma = window.catnormasnacionales.find(
                n => n.ID_NORMAS_NACIONALES == idNorma
            );

            selectApartado.innerHTML = "";

            if (!norma) {
                selectApartado.innerHTML = `<option value="">Error al cargar apartados</option>`;
                return;
            }

            let apartados = norma.APARTADO_NACIONALES_JSON;

            if (typeof apartados === "string") {
                try { apartados = JSON.parse(apartados); } catch (e) { apartados = []; }
            }

            if (!Array.isArray(apartados)) apartados = [];

            if (apartados.length === 0) {
                selectApartado.innerHTML = `
                    <option value="">Esta norma no tiene apartados registrados</option>
                `;
                return;
            }

            let opciones = `<option value="">Seleccione un apartado</option>`;
            apartados.forEach(ap => {
                opciones += `
                    <option value="${ap.APARTADO_ESPECIFICO_NACIONALES}"
                        ${apartadoPreseleccionado == ap.APARTADO_ESPECIFICO_NACIONALES ? "selected" : ""}>
                        ${ap.APARTADO_ESPECIFICO_NACIONALES}
                    </option>`;
            });

            selectApartado.innerHTML = opciones;
        }

        cargarApartados(normaSeleccionada, apartadoSeleccionado);

        selectNorma.addEventListener("change", function() {
            cargarApartados(this.value);
        });

        fila.querySelector('.botonEliminarNomnacionales')
            .addEventListener('click', function() {
                fila.remove();
            });

    });

}

function mostrarNormasInternacionales(row) {

    const contenedor = document.querySelector('.listanominternacionales');
    contenedor.innerHTML = "";  


    let data = row.data().NORMASINTERNACIONALES_EPP;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    data.forEach((item, index) => {

        let normaSeleccionada = item.NOM_INTERNACIONALES;
        let apartadoSeleccionado = item.APARTADO_INTERNACIONALES;

        const fila = document.createElement('div');
        fila.classList.add('row', 'generarnominternacional', 'mb-3');

        let opcionesNormas = `<option value="">Seleccione una norma</option>`;

        window.catnormasinternacionales.forEach(norma => {
            opcionesNormas += `
                <option value="${norma.ID_NORMAS_INTERNACIONALES}" 
                    ${norma.ID_NORMAS_INTERNACIONALES == normaSeleccionada ? "selected" : ""}>
                    ${norma.NOMBRE_NORMA_INTERNACIONALES}
                </option>`;
        });

        fila.innerHTML = `
            <div class="col-6">
                <div class="form-group">
                    <label>Norma/estándar internacional *</label>
                    <select class="form-control select-norma-internacional" 
                        name="NOM_INTERNACIONALES" required>
                        ${opcionesNormas}
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label>Apartado específico *</label>
                    <select class="form-control select-apartado-internacional"
                        name="APARTADO_INTERNACIONALES" required>
                    </select>
                </div>
            </div>

            <div class="col-12 text-center">
                <button type="button" class="btn btn-danger botonEliminarNominternacionales">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;

        contenedor.appendChild(fila);

        const selectNorma = fila.querySelector('.select-norma-internacional');
        const selectApartado = fila.querySelector('.select-apartado-internacional');

        function cargarApartados(idNorma, apartadoPreseleccionado = "") {

            const norma = window.catnormasinternacionales.find(
                n => n.ID_NORMAS_INTERNACIONALES == idNorma
            );

            selectApartado.innerHTML = "";

            if (!norma) {
                selectApartado.innerHTML = `<option value="">Error al cargar apartados</option>`;
                return;
            }

            let apartados = norma.APARTADO_INTERNACIONALES_JSON;

            if (typeof apartados === "string") {
                try { apartados = JSON.parse(apartados); } catch (e) { apartados = []; }
            }

            if (!Array.isArray(apartados)) apartados = [];

            if (apartados.length === 0) {
                selectApartado.innerHTML = `
                    <option value="">Esta norma no tiene apartados registrados</option>
                `;
                return;
            }

            let opciones = `<option value="">Seleccione un apartado</option>`;
            apartados.forEach(ap => {
                opciones += `
                    <option value="${ap.APARTADO_ESPECIFICO_INTERNACIONALES}"
                        ${apartadoPreseleccionado == ap.APARTADO_ESPECIFICO_INTERNACIONALES ? "selected" : ""}>
                        ${ap.APARTADO_ESPECIFICO_INTERNACIONALES}
                    </option>`;
            });

            selectApartado.innerHTML = opciones;
        }

        cargarApartados(normaSeleccionada, apartadoSeleccionado);

        selectNorma.addEventListener("change", function() {
            cargarApartados(this.value);
        });

        fila.querySelector('.botonEliminarNominternacionales')
            .addEventListener('click', function() {
                fila.remove();
            });

    });

}



//=======================================
// CATALOGO REGION ANATOMICA
//=======================================

$("#boton_guardar_regionanatomica").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_region_anatomica').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_region_anatomica(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_regionanatomica').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_regionanatomica').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_regionanatomica').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_regionanatomica').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_region_anatomica(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_regionanatomica != null)
        {
            datatable_regionanatomica.clear().draw();
            datatable_regionanatomica.ajax.url(ruta).load();
        }
        else
        {
            datatable_regionanatomica = $('#tabla_lista_regionanatomica').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_region_anatomica(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_REGION"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_region_anatomica(num_catalogo);
    }    
}

function editar_cat_regionanatomica()
{
    $('#tabla_lista_regionanatomica tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_regionanatomica.row(tr);

        $('#form_region_anatomica').each(function(){
            this.reset();
        });

        $("#ID_REGION_ANATOMICA").val(row.data().ID_REGION_ANATOMICA);
        $("#NOMBRE_REGION").val(row.data().NOMBRE_REGION);
        $("#catalogo").val(catalogo);

        $('#modal_regionanatomica').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO CLAVE Y EPP
//=======================================

$("#boton_guardar_claveyepp").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_claveyepp').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_claveyepp(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_claveyepp').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_claveyepp').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_claveyepp').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_claveyepp').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_claveyepp(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_claveyepp != null)
        {
            datatable_claveyepp.clear().draw();
            datatable_claveyepp.ajax.url(ruta).load();
        }
        else
        {
            datatable_claveyepp = $('#tabla_lista_claveyepp').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_claveyepp(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "REGION_ANATOMICA_NOMBRE"
                    },
                     {
                        "data": "CLAVE_EPP"
                    },
                     {
                        "data": "TIPO_RIESGO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                 rowsGroup: [0],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_claveyepp(num_catalogo);
    }    
}

function editar_cat_claveyepp()
{
    $('#tabla_lista_claveyepp tbody').on('click', 'td.editar', function() {


        var tr = $(this).closest('tr');
        var row = datatable_claveyepp.row(tr);

        $('#form_claveyepp').each(function(){
            this.reset();
        });

   
        $("#ID_CLAVE_EPP").val(row.data().ID_CLAVE_EPP);
        $("#REGION_ANATOMICA_ID").val(row.data().REGION_ANATOMICA_ID);
        $("#CLAVE").val(row.data().CLAVE);
        $("#EPP").val(row.data().EPP);
        $("#TIPO_RIESGO").val(row.data().TIPO_RIESGO);
        $("#NOTA_CLAVE").val(row.data().NOTA_CLAVE);
       

        $("#catalogo").val(catalogo);

        $('#modal_claveyepp').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO MARCAS
//=======================================

$("#boton_guardar_marca").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_marcas').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_marcas(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_marca').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_marcas').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_marca').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_marca').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_marcas(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_marcas != null)
        {
            datatable_marcas.clear().draw();
            datatable_marcas.ajax.url(ruta).load();
        }
        else
        {
            datatable_marcas = $('#tabla_lista_marcas').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_marcas(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_MARCA"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_marcas(num_catalogo);
    }    
}

function editar_cat_marcas()
{
    $('#tabla_lista_marcas tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_marcas.row(tr);

        $('#form_marcas').each(function(){
            this.reset();
        });

        $("#ID_MARCAS_EPP").val(row.data().ID_MARCAS_EPP);
        $("#NOMBRE_MARCA").val(row.data().NOMBRE_MARCA);
        $("#catalogo").val(catalogo);

        $('#modal_marcas').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO NORMAS NACIONALES
//=======================================

document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarnotasnacionales = document.getElementById('botonagregarnotasnacionales');
    botonAgregarnotasnacionales.addEventListener('click', agregarnotasnacionales);

    function agregarnotasnacionales() {

        const contenedor = document.querySelector('.notasnomnacionales');

        let ultimaFila = contenedor.querySelector('.fila-notasnacionales:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-notasnacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Nota *</label>
                <input type="text" class="form-control" name="NOTAS_NACIONALES" required>
                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarnotasnacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarnotasnacionales')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarapartadonacionales = document.getElementById('botonagregarapartadonacionales');
    botonAgregarapartadonacionales.addEventListener('click', agregarapartadonacionales);

    function agregarapartadonacionales() {

        const contenedor = document.querySelector('.apartadonomnacionales');

        let ultimaFila = contenedor.querySelector('.fila-apartadonacionales:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-apartadonacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Apartado específico *</label>
                <input type="text" class="form-control" name="APARTADO_ESPECIFICO_NACIONALES" required>
                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarapartadonacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarapartadonacionales')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

$("#boton_guardar_normasnacionales").click(function (e) {
    e.preventDefault();

    var valida = this.form.checkValidity();
    if (!valida) {
        swal({
            title: "Complete todos los campos",
            text: "Verifique que los datos ingresados concuerden con el tipo de datos",
            type: "error",
            buttons: { visible: false },
            timer: 2000
        });
        return;
    }

    var formData = new FormData($('#form_normas_nacionales')[0]);

 
    var notasnomnacionales = [];

    $("[name='NOTAS_NACIONALES']").each(function() {
        notasnomnacionales.push({
            'NOTAS_NACIONALES': $(this).val()
        });
    });

    formData.append('NOTAS_NACIONALES_JSON', JSON.stringify(notasnomnacionales));


    var apartandonomnacionales = [];

    $("[name='APARTADO_ESPECIFICO_NACIONALES']").each(function() {
        apartandonomnacionales.push({
            'APARTADO_ESPECIFICO_NACIONALES': $(this).val()
        });
    });

    formData.append('APARTADO_NACIONALES_JSON', JSON.stringify(apartandonomnacionales));

  
    $.ajax({
        type: 'POST',
        url: "/eppcatalogos",
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,

        beforeSend: function() {
            $('#boton_guardar_normasnacionales').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
        },

        success: function(dato) {

            tabla_normasnacionales(catalogo);

            swal({
                title: "Correcto",
                text: "Información guardada correctamente",
                type: "success",
                buttons: { visible: false },
                timer: 1500
            });

            $('#boton_guardar_normasnacionales').html('Guardar <i class="fa fa-save"></i>');
            $('#modal_normasnacionales').modal('hide');
        },

        error: function(dato) {

            $('#boton_guardar_normasnacionales').html('Guardar <i class="fa fa-save"></i>');

            swal({
                title: "Error",
                text: "Error al guardar la información",
                type: "error",
                buttons: { visible: false },
                timer: 1500
            });
        }
    });

});

function tabla_normasnacionales(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_normasnacionales != null)
        {
            datatable_normasnacionales.clear().draw();
            datatable_normasnacionales.ajax.url(ruta).load();
        }
        else
        {
            datatable_normasnacionales = $('#tabla_lista_normasnacionales').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_normasnacionales(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "ENTIDAD_TEXTO"
                    },
                    {
                        "data": "NOMBRE_NORMA_NACIONALES"
                    },
                    {
                        "data": "DESCRIPCION_NORMA_NACIONALES"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_normasnacionales(num_catalogo);
    }    
}

function editar_cat_normasnacionales()
{
    $('#tabla_lista_normasnacionales tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_normasnacionales.row(tr);

        $('#form_normas_nacionales').each(function(){
            this.reset();
        });

        $("#ID_NORMAS_NACIONALES").val(row.data().ID_NORMAS_NACIONALES);
        $("#ENTIDAD_NACIONALES").val(row.data().ENTIDAD_NACIONALES);
        $("#NOMBRE_NORMA_NACIONALES").val(row.data().NOMBRE_NORMA_NACIONALES);
        $("#DESCRIPCION_NORMA_NACIONALES").val(row.data().DESCRIPCION_NORMA_NACIONALES);
        $("#catalogo").val(catalogo);

        $('#modal_normasnacionales').modal({ backdrop: false });
        
        
        $(".notasnomnacionales").empty();
        mostrarnotasnomnacionales(row);

        $(".apartadonomnacionales").empty();
        mostrarapartadonomnacionales(row);

    });
}

function mostrarnotasnomnacionales(row) {

    let contenedor = document.querySelector('.notasnomnacionales');

    contenedor.innerHTML = "";

    let data = row.data().NOTAS_NACIONALES_JSON;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-notasnacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Nota *</label>
                <input type="text" class="form-control" 
                       name="NOTAS_NACIONALES" 
                       value="${item.NOTAS_NACIONALES}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarnotasnacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarnotasnacionales')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarapartadonomnacionales(row) {

    let contenedor = document.querySelector('.apartadonomnacionales');

    contenedor.innerHTML = "";

    let data = row.data().APARTADO_NACIONALES_JSON;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-apartadonacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Apartado específico *</label>
                <input type="text" class="form-control" 
                       name="APARTADO_ESPECIFICO_NACIONALES" 
                       value="${item.APARTADO_ESPECIFICO_NACIONALES}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarapartadonacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarapartadonacionales')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

//=======================================
// CATALOGO NORMAS INTERNACIONALES
//=======================================

document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarnotasinternacionales = document.getElementById('botonagregarnotasinternacionales');
    botonAgregarnotasinternacionales.addEventListener('click', agregarnotasinternacionales);

    function agregarnotasinternacionales() {

        const contenedor = document.querySelector('.notasnominternacional');

        let ultimaFila = contenedor.querySelector('.fila-notasinternacionales:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-notasinternacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Nota *</label>
                <input type="text" class="form-control" name="NOTAS_INTERNACIONALES" required>
                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarnotasinternacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarnotasinternacionales')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const botonAgregarapartadointernacionales= document.getElementById('botonagregarapartadointernacionales');
    botonAgregarapartadointernacionales.addEventListener('click', agregarapartadointernacionales);

    function agregarapartadointernacionales() {

        const contenedor = document.querySelector('.apartadonominternacional');

        let ultimaFila = contenedor.querySelector('.fila-apartadointernacionales:last-child');

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-apartadointernacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Apartado específico *</label>
                <input type="text" class="form-control" name="APARTADO_ESPECIFICO_INTERNACIONALES" required>
                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarapartadointernacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarapartadointernacionales')
            .addEventListener('click', function() {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    }
});

$("#boton_guardar_normasinternacionales").click(function (e) {
    e.preventDefault();

    var valida = this.form.checkValidity();
    if (!valida) {
        swal({
            title: "Complete todos los campos",
            text: "Verifique que los datos ingresados concuerden con el tipo de datos",
            type: "error",
            buttons: { visible: false },
            timer: 2000
        });
        return;
    }

    var formData = new FormData($('#form_normas_internacionales')[0]);

 
    var notasnominternacionales = [];

    $("[name='NOTAS_INTERNACIONALES']").each(function() {
        notasnominternacionales.push({
            'NOTAS_INTERNACIONALES': $(this).val()
        });
    });

    formData.append('NOTAS_INTERNACIONALES_JSON', JSON.stringify(notasnominternacionales));


    var apartandonominternacionales = [];

    $("[name='APARTADO_ESPECIFICO_INTERNACIONALES']").each(function() {
        apartandonominternacionales.push({
            'APARTADO_ESPECIFICO_INTERNACIONALES': $(this).val()
        });
    });

    formData.append('APARTADO_INTERNACIONALES_JSON', JSON.stringify(apartandonominternacionales));

  
    $.ajax({
        type: 'POST',
        url: "/eppcatalogos",
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,

        beforeSend: function() {
            $('#boton_guardar_normasinternacionales').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
        },

        success: function(dato) {

            tabla_normasinternacionales(catalogo);

            swal({
                title: "Correcto",
                text: "Información guardada correctamente",
                type: "success",
                buttons: { visible: false },
                timer: 1500
            });

            $('#boton_guardar_normasinternacionales').html('Guardar <i class="fa fa-save"></i>');
            $('#modal_normasinternacionales').modal('hide');
        },

        error: function(dato) {

            $('#boton_guardar_normasinternacionales').html('Guardar <i class="fa fa-save"></i>');

            swal({
                title: "Error",
                text: "Error al guardar la información",
                type: "error",
                buttons: { visible: false },
                timer: 1500
            });
        }
    });

});

function tabla_normasinternacionales(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_normasinternacionales != null)
        {
            datatable_normasinternacionales.clear().draw();
            datatable_normasinternacionales.ajax.url(ruta).load();
        }
        else
        {
            datatable_normasinternacionales = $('#tabla_lista_normasinternacionales').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_normasinternacionales(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                      {
                        "data": "ENTIDAD_TEXTO"
                    },
                    {
                        "data": "NOMBRE_NORMA_INTERNACIONALES"
                    },
                    {
                        "data": "DESCRIPCION_NORMA_INTERNACIONALES"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_normasinternacionales(num_catalogo);
    }    
}

function editar_cat_normasinternacionales()
{
    $('#tabla_lista_normasinternacionales tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_normasinternacionales.row(tr);

        $('#form_normas_internacionales').each(function(){
            this.reset();
        });

        $("#ID_NORMAS_INTERNACIONALES").val(row.data().ID_NORMAS_INTERNACIONALES);
        $("#ENTIDAD_INTERNACIONALES").val(row.data().ENTIDAD_INTERNACIONALES);
        $("#NOMBRE_NORMA_INTERNACIONALES").val(row.data().NOMBRE_NORMA_INTERNACIONALES);
        $("#DESCRIPCION_NORMA_INTERNACIONALES").val(row.data().DESCRIPCION_NORMA_INTERNACIONALES);
        $("#catalogo").val(catalogo);

        $('#modal_normasinternacionales').modal({ backdrop: false });
        

        $(".notasnominternacional").empty();
        mostrarnotasnominternacionales(row);

        $(".apartadonominternacional").empty();
        mostrarapartadonominternacionales(row);


    });
}

function mostrarnotasnominternacionales(row) {

    let contenedor = document.querySelector('.notasnominternacional');

    contenedor.innerHTML = "";

    let data = row.data().NOTAS_INTERNACIONALES_JSON;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-notasinternacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Nota *</label>
                <input type="text" class="form-control" 
                       name="NOTAS_INTERNACIONALES" 
                       value="${item.NOTAS_INTERNACIONALES}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarnotasinternacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarnotasinternacionales')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

function mostrarapartadonominternacionales(row) {

    let contenedor = document.querySelector('.apartadonominternacional');

    contenedor.innerHTML = "";

    let data = row.data().APARTADO_INTERNACIONALES_JSON;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    let ultimaFila = null;

    data.forEach((item, index) => {

        if (!ultimaFila || ultimaFila.querySelectorAll('.col-6').length === 2) {
            ultimaFila = document.createElement('div');
            ultimaFila.classList.add('row', 'fila-apartadointernacionales', 'mb-3');
            contenedor.appendChild(ultimaFila);
        }

        const bloque = document.createElement('div');
        bloque.classList.add('col-6');
        bloque.innerHTML = `
            <div class="form-group">
                <label>Apartado específico *</label>
                <input type="text" class="form-control" 
                       name="APARTADO_ESPECIFICO_INTERNACIONALES" 
                       value="${item.APARTADO_ESPECIFICO_INTERNACIONALES}" required>

                <div class="mt-2" style="text-align:center;">
                    <button type="button" class="btn btn-danger botonEliminarapartadointernacionales">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        ultimaFila.appendChild(bloque);

        bloque.querySelector('.botonEliminarapartadointernacionales')
            .addEventListener('click', function () {
                bloque.remove();

                if (ultimaFila.querySelectorAll('.col-6').length === 0) {
                    ultimaFila.remove();
                }
            });
    });
}

//=======================================
// CATALOGO TALLAS
//=======================================

$("#boton_guardar_tallas").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_tallas').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_talla(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_tallas').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_tallas').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_tallas').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_tallas').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_talla(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_tallas != null)
        {
            datatable_tallas.clear().draw();
            datatable_tallas.ajax.url(ruta).load();
        }
        else
        {
            datatable_tallas = $('#tabla_lista_talla').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_talla(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_TALLA"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_talla(num_catalogo);
    }    
}

function editar_cat_tallas()
{
    $('#tabla_lista_talla tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_tallas.row(tr);

        $('#form_tallas').each(function(){
            this.reset();
        });

        $("#ID_TALLA").val(row.data().ID_TALLA);
        $("#NOMBRE_TALLA").val(row.data().NOMBRE_TALLA);
        $("#catalogo").val(catalogo);

        $('#modal_tallas').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO CLASIFICACION DE RIESGO
//=======================================

$("#boton_guardar_clasificacionriesgo").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_clasificacionriesgo').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_clasificacionriesgo(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_clasificacionriesgo').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_clasificacionriesgo').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_clasificacionriesgo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_clasificacionriesgo').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_clasificacionriesgo(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_clasificacionriesgp != null)
        {
            datatable_clasificacionriesgp.clear().draw();
            datatable_clasificacionriesgp.ajax.url(ruta).load();
        }
        else
        {
            datatable_clasificacionriesgp = $('#tabla_lista_clasificacionriesgo').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_clasificacionriesgo(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "CLASIFICACION_RIESGO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_clasificacionriesgo(num_catalogo);
    }    
}

function editar_cat_clasificacionriesgo()
{
    $('#tabla_lista_clasificacionriesgo tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_clasificacionriesgp.row(tr);

        $('#form_clasificacionriesgo').each(function(){
            this.reset();
        });

        $("#ID_CLASIFICACION_RIESGO").val(row.data().ID_CLASIFICACION_RIESGO);
        $("#CLASIFICACION_RIESGO").val(row.data().CLASIFICACION_RIESGO);
        $("#catalogo").val(catalogo);

        $('#modal_clasificacionriesgo').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO TIPO DE USO
//=======================================

$("#boton_guardar_tipouso").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_tipouso').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {

            },
            resetForm: false,
            success: function (dato) {
                tabla_tipouso(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_tipouso').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_tipouso').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_tipouso').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_tipouso').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_tipouso(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_tipouso != null)
        {
            datatable_tipouso.clear().draw();
            datatable_tipouso.ajax.url(ruta).load();
        }
        else
        {
            datatable_tipouso = $('#tabla_lista_tipouso').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_tipouso(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "TIPO_USO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_tipouso(num_catalogo);
    }    
}

function editar_cat_tipouso()
{
    $('#tabla_lista_tipouso tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_tipouso.row(tr);

        $('#form_tipouso').each(function(){
            this.reset();
        });

        $("#ID_TIPO_USO").val(row.data().ID_TIPO_USO);
        $("#TIPO_USO").val(row.data().TIPO_USO);
        $("#catalogo").val(catalogo);

        $('#modal_tipouso').modal({backdrop:false});
    });
}


//=======================================
// DOCUMENTOS HE IMAGENES DEL EPP
//=======================================

$("#boton_nuevo_epp_documento").click(function()
{
   
    $('#form_epp_documento').each(function(){
        this.reset();
    });

    $("#ID_EPP_DOCUMENTO").val(0);

    $("#IMAGEN_DOCUMENTOS").hide();
    $("#PDF_DOCUMENTOS").hide();

    
     
    if ($('#FOTO_DOCUMENTO').data('dropify'))
    {
        $('#FOTO_DOCUMENTO').dropify().data('dropify').resetPreview();
        $('#FOTO_DOCUMENTO').dropify().data('dropify').clearElement();
        
    }
    else
    {
        
        $('#FOTO_DOCUMENTO').dropify({
            messages: {
                'default': 'Arrastre la imagen aquí o haga click',
                'replace': 'Arrastre la imagen o haga clic para reemplazar',
                'remove':  'Quitar',
                'error':   'Ooops, ha ocurrido un error.'
            },
            error: {
                'fileSize': 'Demasiado grande ({{ value }} max).',
                'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
            }
        });
    }

    $('#modal_epp_documento').modal({backdrop:false});
         
});

$("#DOCUMENTO_TIPO").change(function () {
    let tipo = $(this).val();

    if (tipo == "1") { 
        $("#PDF_DOCUMENTOS").show();
        $("#IMAGEN_DOCUMENTOS").hide();
    } 
    else if (tipo == "2") { 
        $("#IMAGEN_DOCUMENTOS").show();
        $("#PDF_DOCUMENTOS").hide();
    }
    else {
        $("#PDF_DOCUMENTOS").hide();
        $("#IMAGEN_DOCUMENTOS").hide();
    }
});

$("#boton_guardar_epp_documento").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_epp_documento').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {
            
                EPP_ID: CAT_EPP_ID,

            },
            resetForm: false,
            success: function (dato) {
               tabla_epp_documentos(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_epp_documento').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_epp_documento').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_epp_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_epp_documento').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_epp_documentos()
{
    try 
    {
        var ruta = "/tablaeppdocumento/" + CAT_EPP_ID;

        if (TablaEppDocumentos != null)
        {
            // Recargar tabla
            TablaEppDocumentos.clear().draw();
            TablaEppDocumentos.ajax.url(ruta).load();
        }
        else
        {
            TablaEppDocumentos = $('#tabla_epp_documentos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        console.error("Error AJAX tabla_epp_documentos:", xhr.responseText);
                    }
                },
                "columns": [
                    {
                        "data": "numero_registro" 
                    },
                     {
                        "data": "TIPO_DOCUMENTO_TEXTO"
                    },
                    {
                        "data": "NOMBRE_DOCUMENTO"
                    },
                   {
                        "className": 'Pdf',
                        "orderable": false,
                        "data": null,
                        "render": function (data, type, row) {

                            if (row.DOCUMENTO_TIPO == 1) {
                                return '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>';
                            }

                            if (row.DOCUMENTO_TIPO == 2) {
                                    return row.FOTO_TABLA_DOCUMENTO;
                            }

                            return "-";
                        }
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": "boton_editar",
                        "defaultContent": "-"
                    }
                ],
                "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
                "order": [[0, "asc"]],
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class=\"fa fa-spin fa-spinner fa-3x\"></i>",
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
        console.error("Error general tabla_epp_documentos():", exception);
    }
}

$('#tabla_epp_documentos tbody').on('click', 'td.Pdf', function (event) {

    var tr = $(this).closest('tr');
    var row = TablaEppDocumentos.row(tr).data();

    if (row.DOCUMENTO_TIPO == 2) {
        event.stopImmediatePropagation();
        return false;
    }

    $('#modal_visor').modal({backdrop:false});

    $('#nombre_documento_visor').html(row.NOMBRE_DOCUMENTO);

    $('#visor_documento').attr(
        'src',
        '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/vereeppdocumentopdf/' + row.ID_EPP_DOCUMENTO
    );
});

$(document).ready(function()
{
    $('#tabla_epp_documentos tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaEppDocumentos.row(tr);

        $('#form_epp_documento')[0].reset();

        $("#ID_EPP_DOCUMENTO").val(row.data().ID_EPP_DOCUMENTO);
        $("#DOCUMENTO_TIPO").val(row.data().DOCUMENTO_TIPO);
        $("#NOMBRE_DOCUMENTO").val(row.data().NOMBRE_DOCUMENTO);

        if (row.data().DOCUMENTO_TIPO == 1) {  
            $("#PDF_DOCUMENTOS").show();
            $("#IMAGEN_DOCUMENTOS").hide();
        }
        else if (row.data().DOCUMENTO_TIPO == 2) { 
            $("#IMAGEN_DOCUMENTOS").show();
            $("#PDF_DOCUMENTOS").hide();
        }
        else {
            $("#PDF_DOCUMENTOS").hide();
            $("#IMAGEN_DOCUMENTOS").hide();
        }

 
      
         if (row.data().FOTO_DOCUMENTO) {
                var archivo = row.data().FOTO_DOCUMENTO;
                var extension = archivo.substring(archivo.lastIndexOf("."));
                var imagenUrl = '/vereppfotodocumento/' + row.data().ID_EPP_DOCUMENTO + extension;

                if ($('#FOTO_DOCUMENTO').data('dropify')) {
                    $('#FOTO_DOCUMENTO').dropify().data('dropify').destroy();
                    $('#FOTO_DOCUMENTO').dropify().data('dropify').settings.defaultFile = imagenUrl;
                    $('#FOTO_DOCUMENTO').dropify().data('dropify').init();
                } else {
                    $('#FOTO_DOCUMENTO').attr('data-default-file', imagenUrl);
                    $('#FOTO_DOCUMENTO').dropify({
                        messages: {
                            'default': 'Arrastre la imagen aquí o haga click',
                            'replace': 'Arrastre la imagen o haga clic para reemplazar',
                            'remove': 'Quitar',
                            'error': 'Ooops, ha ocurrido un error.'
                        },
                        error: {
                            'fileSize': 'Demasiado grande ({{ value }} max).',
                            'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                            'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                            'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                            'maxHeight': 'Alto demasiado grande (max {{ value }}px).',
                            'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                        }
                    });
                }
            } else {
                $('#FOTO_DOCUMENTO').dropify().data('dropify').resetPreview();
                $('#FOTO_DOCUMENTO').dropify().data('dropify').clearElement();
            }
       

        // Abrir modal
        $('#modal_epp_documento').modal({backdrop:false});
    });
});

//=======================================
// CATALOGO ENTIDADES
//=======================================

$("#boton_guardar_entidades").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_entidades').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/eppcatalogos',
            data: {

            },
            resetForm: false,
            success: function (dato) {
                tabla_entidades(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_entidades').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_entidades').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_entidades').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_entidades').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_entidades(num_catalogo)
{
    var ruta = "/eppconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_entidades != null)
        {
            datatable_entidades.clear().draw();
            datatable_entidades.ajax.url(ruta).load();
        }
        else
        {
            datatable_entidades = $('#tabla_lista_entidades').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_entidades(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
 {
                        "data": "NOMBRE_ENTIDAD"
                    },
                    {
                        "data": "ENTIDAD_DESCRIPCION"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
        tabla_entidades(num_catalogo);
    }    
}

function editar_cat_entidades()
{
    $('#tabla_lista_entidades tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_entidades.row(tr);

        $('#form_entidades').each(function(){
            this.reset();
        });

        $("#ID_ENTIDAD_EPP").val(row.data().ID_ENTIDAD_EPP);
        $("#NOMBRE_ENTIDAD").val(row.data().NOMBRE_ENTIDAD);
        $("#ENTIDAD_DESCRIPCION").val(row.data().ENTIDAD_DESCRIPCION);

        $("#catalogo").val(catalogo);

        $('#modal_entidades').modal({backdrop:false});
    });
}

//=======================================
// FUNCION GLOBAL ACTIVAR/DESACTIVAR 
//=======================================

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
        url: "/eppcatalogodesactiva/"+catalogo+"/"+registro+"/"+estado,
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


