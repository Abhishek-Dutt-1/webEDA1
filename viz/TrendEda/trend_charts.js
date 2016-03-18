// Create Trend Charts
function trendDataLoaded(data) {
	console.log(data);
	
	Highcharts.Point.prototype.tooltipFormatter = function (useHeader) {
    var point = this, series = point.series;
    return ['<br/><span style="color:' + series.color + '"><span>', (point.name || series.name), '</span></span>: ',
        (!useHeader ? ('<b>x = ' + (point.name || point.x) + ',</b> ') : ''),
        '<b>', (!useHeader ? 'y = ' : ''), Highcharts.numberFormat(point.y, 0), '</b>'].join('');
	};
	//console.log(data);
    $('#trendChartContainer').highcharts({
        title: {
            text: data.dependent.name,
			align: 'left',
            x: 50
        },
        subtitle: {
            text: '',
            //x: -20
        },
       xAxis: {
            categories: data.time.data,
			labels: { rotation: -45, maxStaggerLines: 0, step: 3 }

        },
        yAxis: {
            title: {
				text: ''
                //text: data.dependent.name
            },
			gridLineColor: 'transparent',
        },
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'UCL3',
            data: data.dependent.UCL3,
			dashStyle: 'ShortDash',
			color: '#058DC7',
			marker:  { enabled: false },
        }, {
            name: 'UCL2',
            data: data.dependent.UCL2,
			dashStyle: 'ShortDot',
			color: '#50B432',
			marker:  { enabled: false }
        }, {
            name: 'UCL1',
            data: data.dependent.UCL1,
			dashStyle: 'ShortDashDot',
			color: '#ED561B',
			marker:  { enabled: false }
        }, {
            name: 'Average',
            data: data.dependent.Average,
			dashStyle: 'Dot',
			color: '#DDDF00',
			marker:  { enabled: false }
        }, {
            name: 'LCL1',
            data: data.dependent.LCL1,
			dashStyle: 'Dash',
			color: '#24CBE5',
			marker:  { enabled: false }
        }, {
            name: 'LCL2',
            data: data.dependent.LCL2,
			dashStyle: 'LongDash',
			color: '#64E572',
			marker:  { enabled: false }
        }, {
            name: 'LCL3',
            data: data.dependent.LCL3,
			dashStyle: 'ShortDashDotDot',
			color: '#FF9655',
			marker:  { enabled: false }
        }, {
            name: data.dependent.name,
            data: data.dependent.data,
			dashStyle:'Solid',
			color: data.dependent.color,
			marker:  { enabled: false },
        }],
		credits: false
    });
}

// Create EDA Charts
function edaDataLoaded(data) {

	var time = data.time;
	var dep = data.dependent;
	var outerChartDiv = document.createElement('div');

	// Create empty divs for each chart
	data.independent.forEach( function(indep) {
		var chartDiv = document.createElement('div');
		chartDiv.id = indep.name.replace( /[^\w\d]/g, "_");
		chartDiv.className  = "edaChart";
		outerChartDiv.appendChild(chartDiv);
	});

	document.getElementById('edaChartContainer').appendChild(outerChartDiv);			
	
	// Create individual charts
	data.independent.forEach( function(indep) {
		
		drawEDAChart(time, dep, indep);
	});
}

// Create individual EDA Charts
function drawEDAChart(time, dep, indep) {

	$( '#'+indep.name.replace( /[^\w\d]/g, "_") ).highcharts({
        chart: {
            zoomType: 'x'
        },
        title: {
            text: dep.name + " vs. " + indep.name,
			align: 'left',
			x: 50
        },
        xAxis: [{
            categories: time.data,
			labels: { rotation: -45, maxStaggerLines: 0, step: 3 }
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                //format: '{value}°C',
                style: {
                    //color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: dep.name,
                style: {
                    //color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: indep.name,
                style: {
                    //color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                //format: '{value} mm',
                style: {
                    //color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true,
        },
        legend: {
			layout: 'horizontal',
            align: 'bottom',
			x: 40,
			/*
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,*/
            //backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: dep.name,
            type: 'line',
            data: dep.data,
			color: dep.color,
			zIndex: 1
        }, {
            name: indep.name,
            type: 'column',
			//color:'red',
			color: indep.color,
            data: indep.data,
            yAxis: 1,
        }],
		credits: false
    });

}

/////////////////// INIT
$( document ).ready(function() {

	console.log("edaId " + edaId);
	console.log("projectId " + projectId);
/*
	$.get( "viz/TrendEda/Trend.php", { edaId: edaId, projectId: projectId }, trendDataLoaded, "json" ).fail( function(err) {
		console.log("Trend Chart ERROR!");
		console.log(err); 
	});
*/
	$.get( "viz/TrendEda/EDA.php", { edaId: edaId, projectId: projectId }, edaDataLoaded, "json" ).fail( function(err) {
		console.log("EDA Charts ERROR!");
		console.log(err);
	});


});