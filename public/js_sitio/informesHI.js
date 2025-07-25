
//variables globales
var proyecto_id = 0;
var recsensorial_id = 0;
var proyecto_perfil = 0;
var recsensorial_alcancefisico = 0;
var recsensorial_alcancequimico = 0;
var recsensorial_errorvalidacion = 0;
var proyecto_bloqueado = 0;

// Data tables
var datatable_proyectos = null;
var datatable_proyectosInternos = null;
var datatable_proyectoordenservicios = null;

// tablas principales
var datatable_proveedores = 0;
var datatable_signatarios_activo = 0;
var datatable_signatarios = null;
var datatable_equipos_activo = 0;
var datatable_equipos = null;

// Lista de proveedores del sistema
var proveedores_asignados = 0;
var proveedores_lista = '';
// var proveedor_alcances = '';
var proyectoInterno = 0;

// Modulo evidencia
var evidencia_agente_id = 0;
var evidencia_agente = '';

// Modulo reportes
Menureportes_contador = 0;



//===============================================================


// Load PAGINA
$(document).ready(function() 
{

    cargarFoliosProyecto('');



    $('[data-toggle="tooltip"]').tooltip();

});


//=============================================================

function cargarFoliosProyecto(proyecto_folio) {
    const selectElement = document.getElementById('informes_reco');
    if (!selectElement) {
        console.error('Error en consultar los datos');
        return;
    }

    selectElement.innerHTML = '';
	

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/servicioHI",
        data: {},
        cache: false,
        success: function(dato) {
            selectElement.innerHTML = dato.opciones;
			
			$(selectElement).selectize({
                create: false,
                sortField: 'text',
                placeholder: 'Seleccione una opción'
            });
        },

        beforeSend: function() {
            selectElement.innerHTML = '<option value="" selected>Consultando folios de proyectos y reconocimientos...</option>';
        },
        error: function(dato) {
            console.error('Error:', dato.msj);
            return false;
        }
    });
}



var total_ejecusion = 0;
function consulta_menuparametros_reportes(proyecto_id)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteslistaparametros/"+proyecto_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			// Pintar menu opciones
			$('#select_tiporeportes').html(dato.opciones_menu);
		},
		beforeSend: function()
		{
			// $('#lista_menu_parametros_reportes').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></li>');
			$('#select_tiporeportes').html('<option value=""><i class="fa fa-spin fa-spinner""></i></option>');
		},
		error: function(dato)
		{
			if (total_ejecusion == 0)
			{
				consulta_menuparametros_reportes(proyecto_id);
				total_ejecusion += 1;
			}

			// $('#lista_menu_parametros_reportes').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">Error al cargar los parametros</li>');
			$('#select_tiporeportes').html('<option value="">Error al cargar la lista de reportes, actualice la página</option>');
			return false;
		}
	});//Fin ajax
}



function seleccionar_proyectos(ProyectoID) {
	
	//DESBLOQUEAMOS LOS BOTONES Y SELECT PARA LOS REPORTES
	$('#btnPoeProyecto').prop('disabled', true);
	// $('#btnMatriz').prop('disabled', true);

	$('#select_tiporeportes').prop('disabled', true);

	consultarEstatusProyecto(ProyectoID)
	consulta_menuparametros_reportes(ProyectoID)
    document.getElementById('estructura_reporte').innerHTML = '';


}



// function mostrar_reporte(agente_nombre)

function mostrar_reporte(agente_id)
{

var proyecto_id =  $('#informes_reco').val();
//   console.log(agente_id);
	
	// Cargar página reporte
	$.ajax({
		success:function()
		{
			switch (parseInt(agente_id))
			{ 
				case 0:
					$('#estructura_reporte').load('/reportepoevista/'+proyecto_id);
					break;
				case 1:
				    $('#estructura_reporte').load('/reporteruidovista/'+proyecto_id);
					break;
				case 2:
					$('#estructura_reporte').load('/reportevibracionvista/'+proyecto_id);
					break;
				case 3:
					$('#estructura_reporte').load('/reportetemperaturavista/'+proyecto_id);
					break;      
				case 4:
					$('#estructura_reporte').load('/reporteiluminacionvista/'+proyecto_id);
					break;
				case 5:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 6:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 7:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 8:
					$('#estructura_reporte').load('/reporteairevista/'+proyecto_id);
					break;
				case 9:
					$('#estructura_reporte').load('/reporteaguavista/'+proyecto_id);
					break;
				case 10:
					$('#estructura_reporte').load('/reportehielovista/'+proyecto_id);
					break;
				case 11:
					$('#estructura_reporte').load('/reportealimentosvista/'+proyecto_id);
					break;
				case 12:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 13:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 14:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 15:
					$('#estructura_reporte').load('/reportequimicosvista/'+proyecto_id);
					break;
				case 16:
					$('#estructura_reporte').load('/reporteserviciopersonalvista/'+proyecto_id);
					break;
				case 17:
					// $('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					$('#estructura_reporte').load('/reportemapaderiesgovista/'+proyecto_id);
					break;
				case 22:
					$('#estructura_reporte').load('/reportebeivista/'+proyecto_id);
					break;
				case 23:
					$('#estructura_reporte').load('/reportematrizlaboralvista/'+proyecto_id);
					break;
				default:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Seleccione un tipo de reporte</p>');
					break;
			}
		},
		beforeSend: function()
		{
			$('#estructura_reporte').html('<div style="text-align: center; font-size: 60px;"><i class="fa fa-spin fa-spinner"></i></div>');
		},
		error: function()
		{
			$('#estructura_reporte').html('<div style="text-align: center;">Error al cargar parametro</div>');
			return false;
		}
	});//Fin ajax
}

//MOSTRAMOS EL REPORTE DE POE DEL PROYECTO
$('#btnPoeProyecto').on('click', function (e) {
	e.preventDefault();
	
	$('#select_tiporeportes').val('');

	//Consultamos el reporte de POE
	mostrar_reporte(0)

})


function consultarEstatusProyecto(ID_PROYECTO) {

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/estatusProyecto/" + ID_PROYECTO,
        data: {},
        cache: false,
        success: function(dato) {
            
			if (dato.nuevo == 1) { //si es nuevo tiene que realizar el POE
				
				$('#btnPoeProyecto').prop('disabled', false);
				// $('#btnMatriz').prop('disabled', false);
				$('#select_tiporeportes').prop('disabled', true);
				$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Complete la tabla de POE para poder generar los reportes.</p>');

			} else {

				if (dato.info.POE_FINALIZADO == 1) {
					$('#btnPoeProyecto').prop('disabled', false);
				// $('#btnMatriz').prop('disabled', false);

					$('#select_tiporeportes').prop('disabled', false);
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Seleccione un tipo de reporte.</p>');
				} else {
					$('#btnPoeProyecto').prop('disabled', false);
				// $('#btnMatriz').prop('disabled', false);

					$('#select_tiporeportes').prop('disabled', true);
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Complete la tabla de POE para poder generar los reportes.</p>');
				}
				
			}

        },
        beforeSend: function() {
			$('#btnPoeProyecto').prop('disabled', true);
        //    $('#btnMatriz').prop('disabled', true);
			
			$('#select_tiporeportes').prop('disabled', true);

        },
        error: function(dato) {
            console.error('Error:', dato.msj);
            return false;
        }
    });
}




