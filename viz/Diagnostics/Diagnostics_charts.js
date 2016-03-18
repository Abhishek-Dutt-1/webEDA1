function diagnosticsDataLoaded(data) {

	//var time = data.time;
	//var dep = data.dependent;
	console.log(data);
	var outerChartDiv = document.createElement('div');

	// Create empty divs for each chart
	data.independent.forEach( function(indep) {
		var chartDiv = document.createElement('div');
		chartDiv.id = indep.name.replace( /[^\w\d]/g, "_");
		chartDiv.className  = "diagnosticsChart";
		outerChartDiv.appendChild(chartDiv);
	});

	document.getElementById('diagnosticsChartContainer').appendChild(outerChartDiv);			
	
	// Create individual charts
	data.independent.forEach( function(indep) {
		diagnosticsDrawChart(indep);
	});
}

function diagnosticsDrawChart(indep) {

    $( '#'+indep.name.replace( /[^\w\d]/g, "_") ).highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: indep.name,
			align: 'left',
			x: 60
        },
        xAxis: {
            categories: indep.binsTxt,
			labels: { rotation: -45, maxStaggerLines: 0 }
        },
        yAxis: {
            //min: 0,
            title: {
                text: 'Freq'
            }
        },		
		legend: {
            enabled: false
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true,
			enabled: false
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: indep.name,
            data: indep.data,
			color: indep.color

        }],
		credits: false
    });

}
/////////////////// INIT
$( document ).ready(function() {

	$.get( "viz/Diagnostics/Diagnostics.php", { edaId: edaId, projectId: projectId }, diagnosticsDataLoaded, "json" ).fail( function(err) {
		console.log("ERROR");
		console.log(err);
	});

});