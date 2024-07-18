// var chart;

// Load PAGINA
$(document).ready(function()
{
	// var color = Array("#FF0F00", "#FF6600", "#FF9E01", "#FCD202", "#F8FF01", "#B0DE09", "#04D215", "#0D8ECF", "#0D52D1", "#2A0CD0", "#8A0CCF", "#CD0D74", "#754DEB", "#DDDDDD", "#999999", "#333333", "#000000");

	graficas_tablero();
});


var total_ejecusion = 0;
function graficas_tablero()
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/graficas",
		data:{},
		cache: false,
		success:function(dato)
		{
			// alert(dato.reconocimientos_periodo_chartData[0].Periodos);

			grafica_reconocimientos_periodo(dato.reconocimientos_periodo_chartData);
			grafica_proyectos_periodo(dato.proyectos_periodo_chartData);
			grafica_proyectos_detalles(dato.grafica_proyectos_detalles);
			grafica_proyectos_pastel(dato.grafica_pastel, dato.grafica_pastel_anio, dato.grafica_pastel_total_proyectos);
			tabla_proyectos_actuales(dato.tabla_proyectos_actuales)
		},
		beforeSend: function()
		{
			$('#div_grafica_reconocimientos_periodo').html('<i class="fa fa-spin fa-spinner fa-5x"></i>');
			$('#div_grafica_proyectos_periodo').html('<i class="fa fa-spin fa-spinner fa-5x"></i>');
			$('#div_grafica_proyectos_detalles').html('<i class="fa fa-spin fa-spinner fa-5x"></i>');
			$('#div_grafica_proyectos_pastel').html('<i class="fa fa-spin fa-spinner fa-5x"></i>');
		},
		error: function(dato)
		{
			if (total_ejecusion == 0)
			{
				graficas_tablero();
				total_ejecusion += 1;
			}

			$('#div_grafica_reconocimientos_periodo').html('<i class="fa fa-times fa-5x"></i>');
			$('#div_grafica_proyectos_periodo').html('<i class="fa fa-times fa-5x"></i>');
			$('#div_grafica_proyectos_detalles').html('<i class="fa fa-times fa-5x"></i>');
			$('#div_grafica_proyectos_pastel').html('<i class="fa fa-times fa-5x"></i>');
			return false;
		}
	});//Fin ajax
}


function grafica_reconocimientos_periodo(chartData)
{
	var chart;

	
	/*
	var chartData = [
	    {
	        "periodo": "2018",
	        "total": 41,
	        "color": "#FF0F00"
	    },
	    {
	        "periodo": "2019",
	        "total": 56,
	        "color": "#04D215"
	    },
	    {
	        "periodo": "2020",
	        "total": 37,
	        "color": "#0D8ECF"
	    }
	];
	*/
	

	// SERIAL CHART
	chart = new AmCharts.AmSerialChart();
	chart.dataProvider = chartData;
	chart.addTitle("Total de reconocimientos por periodo", 15); //Titulo de la grafica
	chart.categoryField = "periodo";
	chart.startDuration = 1.5;
	chart.marginTop = 20;
	chart.marginRight = 15;
	chart.marginBottom = 0;
	chart.marginLeft = 0;
	chart.fontSize = 12; //Tamaño de letra
	// ----- Efecto 3D -----
	// chart.depth3D = 20;
	// chart.angle = 30;


	// category eje X
	var categoryAxis = chart.categoryAxis;
	// categoryAxis.title = "Periodos"; //Titulo eje de las X
	categoryAxis.labelRotation = 0;
	categoryAxis.gridAlpha = 0; //Linea transparencia
	categoryAxis.dashLength = 5; //Puntear linea
	categoryAxis.gridPosition = "start";


	// value eje Y
	var valueAxis = new AmCharts.ValueAxis();
	valueAxis.title = ""; //Titulo eje de las Y
	valueAxis.gridAlpha = 0.1; //Linea transparencia
	valueAxis.dashLength = 5; //Puntear linea
	valueAxis.autoGridCount = false;
	valueAxis.minimum = 0;
	// valueAxis.gridCount = 10;
	// valueAxis.labelFrequency = 10;
	chart.addValueAxis(valueAxis);


	// GRAPH
	var graph = new AmCharts.AmGraph();
	graph.valueField = "total";
	graph.colorField = "color";
	graph.labelText = "[[value]]";
	graph.labelPosition = "top"; // "bottom", "top", "right", "left", "inside", "middle"
	graph.balloonText = "<span style='font-size:14px;'><b style='font-weight: 900;'>Total [[category]]</b>: [[value]]</span>";
	graph.type = "column";
	graph.lineAlpha = 0;
	graph.fillAlphas = 0.7; // 0.5 Transparencia
	graph.lineThickness = 1;
	chart.addGraph(graph);


	// CURSOR
	var chartCursor = new AmCharts.ChartCursor();
	chartCursor.cursorAlpha = 0; // Linea position
	chartCursor.zoomable = false; //Hacer Zoom a las series
	chartCursor.categoryBalloonEnabled = true;
	chartCursor.valueBalloonsEnabled = false; //true = muestra todos los globos de la serie, false = muestra 1 x 1
	chart.addChartCursor(chartCursor);


	chart.creditsPosition = "top-right";


	chart["export"] = {
	  "enabled": true
	};


	// WRITE
	chart.write("div_grafica_reconocimientos_periodo");
}


function grafica_proyectos_periodo(chartData)
{
	var chart;


	/*
	var chartData = [
	    {
	        "periodo": "2018",
	        "total": 41,
	        "color": "#FF0F00"
	    },
	    {
	        "periodo": "2019",
	        "total": 56,
	        "color": "#04D215"
	    },
	    {
	        "periodo": "2020",
	        "total": 37,
	        "color": "#0D8ECF"
	    }
	];
	*/


	// SERIAL CHART
	chart = new AmCharts.AmSerialChart();
	chart.dataProvider = chartData;
	chart.addTitle("Total de proyectos por periodo", 15); //Titulo de la grafica
	chart.categoryField = "periodo";
	chart.startDuration = 1.5;
	chart.marginTop = 20;
	chart.marginRight = 15;
	chart.marginBottom = 0;
	chart.marginLeft = 0;
	chart.fontSize = 12; //Tamaño de letra
	// ----- Efecto 3D -----
	// chart.depth3D = 20;
	// chart.angle = 30;


	// category eje X
	var categoryAxis = chart.categoryAxis;
	// categoryAxis.title = "Periodos"; //Titulo eje de las X
	categoryAxis.labelRotation = 0;
	categoryAxis.gridAlpha = 0; //Linea transparencia
	categoryAxis.dashLength = 5; //Puntear linea
	categoryAxis.gridPosition = "start";


	// value eje Y
	var valueAxis = new AmCharts.ValueAxis();
	valueAxis.title = ""; //Titulo eje de las Y
	valueAxis.gridAlpha = 0.1; //Linea transparencia
	valueAxis.dashLength = 5; //Puntear linea
	valueAxis.autoGridCount = false;
	valueAxis.minimum = 0;
	// valueAxis.gridCount = 10;
	// valueAxis.labelFrequency = 10;
	chart.addValueAxis(valueAxis);


	// GRAPH
	var graph = new AmCharts.AmGraph();
	graph.valueField = "total";
	graph.colorField = "color";
	graph.labelText = "[[value]]";
	graph.labelPosition = "top"; // "bottom", "top", "right", "left", "inside", "middle"
	graph.balloonText = "<span style='font-size:14px;'><b style='font-weight: 900;'>Total [[category]]</b>: [[value]]</span>";
	graph.type = "column";
	graph.lineAlpha = 0;
	graph.fillAlphas = 0.7; // 0.5 Transparencia
	graph.lineThickness = 1;
	chart.addGraph(graph);

	// CURSOR
	var chartCursor = new AmCharts.ChartCursor();
	chartCursor.cursorAlpha = 0; // Linea position
	chartCursor.zoomable = false; //Hacer Zoom a las series
	chartCursor.categoryBalloonEnabled = true;
	chartCursor.valueBalloonsEnabled = false; //true = muestra todos los globos de la serie, false = muestra 1 x 1
	chart.addChartCursor(chartCursor);


	chart.creditsPosition = "top-right";


	chart["export"] = {
		"enabled": true
	};


	// WRITE
	chart.write("div_grafica_proyectos_periodo");
}


function grafica_proyectos_detalles(chartData)
{
	var chart;


	
	// var chartData = [
	//     {
	//         "periodo": 2005,
	//         "activos": 23,
	//         "reprogramados": 12,
	//         "penalizados": 34,
	//         "concluidos": 21
	//     },
	//     {
	//         "periodo": 2006,
	//         "activos": 26,
	//         "reprogramados": 12,
	//         "penalizados": 34,
	//         "concluidos": 21
	//     },
	//     {
	//         "periodo": 2007,
	//         "activos": 30,
	//         "reprogramados": 12,
	//         "penalizados": 34,
	//         "concluidos": 21
	//     },
	//     {
	//         "periodo": 2008,
	//         "activos": 29,
	//         "reprogramados": 12,
	//         "penalizados": 34,
	//         "concluidos": 21
	//     },
	//     {
	//         "periodo": 2009,
	//         "activos": 24,
	//         "reprogramados": 12,
	//         "penalizados": 2,
	//         "concluidos": 21
	//     }
	// ];
	


    // SERIAL CHART
    chart = new AmCharts.AmSerialChart();
    chart.dataProvider = chartData;
    chart.addTitle("Estado de los proyectos por periodo", 15); //Titulo de la grafica
    chart.startDuration = 1.5;
    chart.plotAreaBorderColor = "#000000";
    chart.plotAreaBorderAlpha = 0;
    chart.marginTop = 20;
	chart.marginRight = 15;
	chart.marginBottom = 0;
	chart.marginLeft = 0;
	chart.fontSize = 12; //Tamaño de letra
    chart.rotate = false;
    chart.categoryField = "periodo";
    // ----- Efecto 3D -----
	// chart.depth3D = 20;
	// chart.angle = 30;


    // category eje X
    var categoryAxis = chart.categoryAxis;
    // categoryAxis.title = "Periodos"; //Titulo eje de las X
    categoryAxis.gridPosition = "right";
    categoryAxis.labelRotation = 0;
	categoryAxis.gridAlpha = 0; //Linea transparencia
	categoryAxis.dashLength = 5; //Puntear linea
    categoryAxis.axisAlpha = 1; //Linea eje


    // Value
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.position = "left"; //Top, right, bottom, left
    valueAxis.axisAlpha = 1; //Linea eje
    valueAxis.gridAlpha = 0.1;
    valueAxis.dashLength = 5; //Puntear linea
    valueAxis.autoGridCount = false;
	valueAxis.minimum = 0;
	// valueAxis.gridCount = 10;
	// valueAxis.labelFrequency = 10;
    chart.addValueAxis(valueAxis);


    // GRAPHS
    // graph 1
    var graph1 = new AmCharts.AmGraph();
    graph1.type = "column";
    graph1.title = "Concluidos";
    graph1.valueField = "concluidos";
    graph1.colorField = "color";
    graph1.labelText = "[[value]]";
    graph1.labelPosition = "top"; // "bottom", "top", "right", "left", "inside", "middle"
    graph1.balloonText = "<span style='font-size:14px;'><b style='font-weight: 900;'>Concluidos</b>: [[value]]</span>"; //[[category]], [[value]]
    graph1.lineAlpha = 0;
    graph1.fillColors = "#04D215";
    graph1.fillAlphas = 0.8; // 0.5 Transparencia
    graph1.lineThickness = 1;
    chart.addGraph(graph1);


    // graph 2
    var graph2 = new AmCharts.AmGraph();
    graph2.type = "column";
    graph2.title = "Penalizados";
    graph2.valueField = "penalizados";
    graph2.colorField = "color";
    graph2.labelText = "[[value]]";
    graph2.labelPosition = "top"; // "bottom", "top", "right", "left", "inside", "middle"    
    graph2.balloonText = "<span style='font-size:14px;'><b style='font-weight: 900;'>Penalizados</b>: [[value]]</span>"; //[[category]], [[value]]
    graph2.lineAlpha = 0;
    graph2.fillColors = "#fc4b6c";
    graph2.fillAlphas = 0.8; // 0.5 Transparencia
    graph2.lineThickness = 1;
    chart.addGraph(graph2);


    // graph 3
    var graph3 = new AmCharts.AmGraph();
    graph3.type = "column";
    graph3.title = "Reprogramados";
    graph3.valueField = "reprogramados";
    graph3.colorField = "color";
    graph3.labelText = "[[value]]";
    graph3.labelPosition = "top"; // "bottom", "top", "right", "left", "inside", "middle"    
    graph3.balloonText = "<span style='font-size:14px;'><b style='font-weight: 900;'>Reprogramados</b>: [[value]]</span>"; //[[category]], [[value]]
    graph3.lineAlpha = 0;
    graph3.fillColors = "#FCD202";
    graph3.fillAlphas = 0.8; // 0.5 Transparencia
    graph3.lineThickness = 1;
    chart.addGraph(graph3);


    // graph 4
    var graph4 = new AmCharts.AmGraph();
    graph4.type = "column";
    graph4.title = "Activos";
    graph4.valueField = "activos";
    graph4.colorField = "color";
    graph4.labelText = "[[value]]";
    graph4.labelPosition = "top"; // "bottom", "top", "right", "left", "inside", "middle"    
    graph4.balloonText = "<span style='font-size:14px;'><b style='font-weight: 900;'>Activos</b>: [[value]]</span>"; //[[category]], [[value]]
    graph4.lineAlpha = 0;
    graph4.fillColors = "#26c6da";
    graph4.fillAlphas = 0.8; // 0.5 Transparencia
    graph4.lineThickness = 1;
    chart.addGraph(graph4);


    // LEGEND
    legend = new AmCharts.AmLegend();
    legend.align = "center";
    legend.markerType = "circle"; //square, circle, diamond, triangleUp, triangleDown, triangleLeft, triangleDown, bubble, line, none.
    legend.maxColumns = 20;
    legend.position = "bottom"; //top, right, bottom, left
    legend.marginRight = 20;
    legend.valueText = "[[value]]";
    legend.valueWidth = 0; // Distancia para mostrar el valor de la etiqueta
    legend.switchable = true;
    legend.labelText = "[[title]]";
    chart.addLegend(legend);


    chart["export"] = {
		"enabled": true
	};


	// CURSOR
	var chartCursor = new AmCharts.ChartCursor();
	chartCursor.cursorAlpha = 0; // Linea position
	chartCursor.zoomable = false; //Hacer Zoom a las series
	chartCursor.categoryBalloonEnabled = true;
	chartCursor.valueBalloonsEnabled = false; //true = muestra todos los globos de la serie, false = muestra 1 x 1
	chart.addChartCursor(chartCursor);


	// WRITE
	chart.write("div_grafica_proyectos_detalles");
}


function grafica_proyectos_pastel(chartData, anio, total_proyectos)
{
	var chart;


	/*
    var chartData = [
        {
            "estado": "United States",
            "total": 9252
        },
        {
            "estado": "China",
            "total": 1882
        },
        {
            "estado": "Japan",
            "total": 1809
        },
        {
            "estado": "Germany",
            "total": 1322
        },
        {
            "estado": "United Kingdom",
            "total": 1122
        },
        {
            "estado": "France",
            "total": 1114
        },
        {
            "estado": "India",
            "total": 984
        },
        {
            "estado": "Spain",
            "total": 711
        }
    ];
    */


    // PIE CHART
    chart = new AmCharts.AmPieChart();

    // title of the chart
    chart.addTitle("Estado de los proyectos "+anio, 16);


    chart.dataProvider = chartData;
    chart.startDuration = 1.5;
	chart.marginTop = 0;
	chart.marginRight = 0;
	chart.marginBottom = 0;
	chart.marginLeft = 0;
	chart.fontSize = 14; //Tamaño de letra
    chart.titleField = "estado";
    chart.valueField = "total";
    chart.colorField = "color";
    chart.sequencedAnimation = true;
    chart.startEffect = "elastic";
    chart.innerRadius = "70%";
    chart.radius = "40%";
    chart.labelRadius = 15;
    chart.balloonText = "<b style='font-weight: 900;'>[[title]]</b><br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
    // chart.labelText = "[[title]]: [[value]]\n([[percents]]%)";
    // chart.numberFormatter = {precision: 0, decimalSeparator:".", thousandsSeparator:","}; //Formato numero 000,000.00
    chart.labelRadius = -10; //Distancia de la etiqueta de la serie
    chart.alignLabels = false;
    chart.outlineAlpha = 1;
    chart.outlineColor = "#FFFFFF"; //Color linea borde
    chart.outlineThickness = 1; //transparencia linea borde
    // ----- Efecto 3D -----
    // chart.depth3D = 10;
    // chart.angle = 15;


    // Editar etiquetas, numero sin decimales
	chart["labelFunction"] = function(item)
	{
		var value = Math.round( item.value, 2 ); // format value
		var percent = Math.round( item.percents, 2 ); // format percent
		return item.title + "\n" + value + " (" + percent + "%)"; // para salto de linea usar \n
	};



	chart["allLabels"] = [
		{
			"text": "Total",
			"size": 40,
			"color": "#000000",
			"bold": true,
			"alpha": 0.1, //Transparencia
			"rotation": 0, 
			// "url": "http://www.google.com.mx",
			"align": "center",
			// "x": 100,
			"y": 230,
		},
		{
			"text": ""+total_proyectos,
			"size": 90,
			"color": "#000000",
			"bold": true,
			"alpha": 0.1, //Transparencia
			"rotation": 0, 
			// "url": "http://www.google.com.mx",
			"align": "center",
			// "x": 100,
			"y": 260,
		}
	];


    chart["export"] = {
		"enabled": true
	};


    // LEGEND
    legend = new AmCharts.AmLegend();
    legend.align = "center";
    legend.markerType = "circle"; //square, circle, diamond, triangleUp, triangleDown, triangleLeft, triangleDown, bubble, line, none.
    legend.maxColumns = 20;
    legend.position = "bottom"; //top, right, bottom, left
    legend.marginRight = 20;
    legend.valueText = "[[value]]";
    legend.valueWidth = 0; // Distancia para mostrar el valor de la etiqueta
    legend.switchable = true;
    legend.labelText = "[[title]]";
    // chart.addLegend(legend);


    // WRITE
    chart.write("div_grafica_proyectos_pastel");
}


var intentos = 0;
var datatable_proyectos_actuales = null;
function tabla_proyectos_actuales(tbody)
{
	if (datatable_proyectos_actuales != null)
	{
		datatable_proyectos_actuales.destroy();
	}


	$('#tabla_proyectos_actuales tbody').html(tbody);


	datatable_proyectos_actuales = $('#tabla_proyectos_actuales').DataTable({
		"scrollY": "425px",
        "scrollCollapse": true,
		"lengthMenu": [[1000, 5000, 10000, -1], [1000, 5000, 10000, "Todos"]],
		// "rowsGroup": [1, 3], //agrupar filas
		"order": [[ 0, "asc" ]],
		"ordering": true,
		"searching": true,
		"processing": true,
		"paging": false,
		"info": false,
		"language": {
			"lengthMenu": "Mostrar _MENU_ Registros",
			"zeroRecords": "No se encontraron registros",
			"info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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
		},
		rowCallback: function(row, data, index)
		{
			if (data[8] == "Concluido")
			{
				$('td', row).eq(8).css('color', '#04D215');
			}
			else if (data[8] == "Penalizado")
			{
				$('td', row).eq(8).css('color', '#fc4b6c');
			}
			else if (data[8] == "Reprogramado")
			{
				$('td', row).eq(8).css('color', '#FCD202');
			}
			else
			{
				$('td', row).eq(8).css('color', '#1e88e5');
			}
			
			
			//------------------


			// if(data.proyecto_estado == "Activo")
			// {
			// 	$(row).find('td:eq(0)').css('background', "#00FF00");
			// 	$(row).find('td:eq(0)').css('color', '#000000');
			// 	$(row).find('td:eq(0)').css('font-weight', 'bold');
			// }
			// else
			// {
			// 	$(row).find('td:eq(0)').css('background', "#FF0000");
			// 	$(row).find('td:eq(0)').css('color', '#FFFFFF');
			// 	$(row).find('td:eq(0)').css('font-weight', 'bold');
			// }
		},
	});
}