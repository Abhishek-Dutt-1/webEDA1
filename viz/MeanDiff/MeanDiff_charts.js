function meanDiffDataLoaded(data) {

	var time = data.time;
	var dep = data.dependent;
	var outerChartDiv = document.createElement('div');

	// Create empty divs for each chart
	data.independent.forEach( function(indep) {
		var chartDiv = document.createElement('div');
		chartDiv.id = indep.name.replace( /[^\w\d]/g, "_");;
		chartDiv.className  = "meanDiffChart";
		outerChartDiv.appendChild(chartDiv);
	});

	document.getElementById('meanDiffChartContainer').appendChild(outerChartDiv);			
	
	// Create individual charts
	data.independent.forEach( function(indep) {
		meanDiffDrawChart(time, dep, indep);
	});
}

// Create individual EDA Charts
function meanDiffDrawChart(time, dep, indep) {

	// Create the data table.
	var i = 0;
	var dataArray = [];
	for(i=0; i < time.data.length; i++) {
		//dataArray.push( [time.data[i], dep.data[i], indep.data[i]] );
		dataArray.push( [dep.data[i], indep.data[i]] );
	}

    $( '#'+indep.name.replace( /[^\w\d]/g, "_") ).highcharts({
        chart: {
            type: 'scatter',
            zoomType: 'xy'
        },
        title: {
            text: dep.name + " vs. " + indep.name,
			align: 'left',
			x: 70
        },
        xAxis: {
			//lineWidth: 0,
			//tickLength: 0,
            title: {
                enabled: true,
                text: dep.name
            },
            startOnTick: true,
            endOnTick: true,
            //showLastLabel: true,
			plotLines : [{
				value : 0,
				color : 'lightgrey',
				dashStyle : 'dash',
				width : 1,
				/* label : {
					text : 'Last quarter maximum'
				}*/
			}]
        },
        yAxis: {
			//lineWidth: 1,
            title: {
                text: indep.name
            },
			gridLineColor: 'transparent',
			plotLines : [{
				value : 0,
				color : 'lightgrey',
				dashStyle : 'dash',
				width : 1,
				/* label : {
					text : 'Last quarter maximum'
				}*/
			}]
        },
        plotOptions: {
            scatter: {
                marker: {
                    radius: 5,
                    states: {
                        hover: {
                            enabled: true,
                            lineColor: 'rgb(100,100,100)'
                        }
                    }
                },
                states: {
                    hover: {
                        marker: {
                            enabled: false
                        }
                    }
                }
            }
        },
		tooltip: {
			headerFormat: '<b>{series.name}</b><br>',
			enabled: false
			//pointFormat: '{point.x} cm, {point.y} kg'
		},
		legend: {
            enabled: false
        },
        series: [{
			
            //showInLegend: false,
			name: indep.name,
            //color: 'rgba(223, 83, 83, .5)',
			color: indep.color,
            data: dataArray,
			regression: true ,
			regressionSettings: {
				showInLegend: false,
				type: 'linear',
				//color:  'rgba(223, 83, 83, .9)'
				color: indep.color
			}
        }],
		credits: false
    });

}


/////////////////// INIT
$( document ).ready(function() {

	//var meanDiffChartData = <?php echo json_encode( getData() ); ?>;
	$.get( "viz/MeanDiff/MeanDiff.php", { edaId: edaId, projectId: projectId }, meanDiffDataLoaded, "json" ).fail( function(err) { 
		console.log("Mean Diff ERROR");
		console.log(err); 
	});

});