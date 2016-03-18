var trendChartData;
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function trendDrawChart(time, dep) {

	//console.log(dep);
	// Create the data table.
	var i = 0;
	var dataArray = [];
	for(i=0; i < dep.data.length; i++) {
		
		dataArray.push( [time.data[i], dep.UCL3[i], dep.UCL2[i], dep.UCL1[i], dep.Average[i], dep.LCL1[i], dep.LCL2[i], dep.LCL3[i], dep.data[i] ] );
	}

	var data = new google.visualization.DataTable();
//		data.addColumn('string', time.name);
		data.addColumn('string', time.name);
		data.addColumn('number', "UCL3");
		data.addColumn('number', "UCL2");
		data.addColumn('number', "UCL1");
		data.addColumn('number', "Average");
		data.addColumn('number', "LCL1");
		data.addColumn('number', "LCL2");
		data.addColumn('number', "LCL3");
		data.addColumn('number', dep.name);
		data.addRows( dataArray );
		/* data.addRows([
			['Mushrooms', 3],
			['Onions', 1],
			['Olives', 1],
			['Zucchini', 1],
			['Pepperoni', 2]
		]);*/

		// Set chart options
		var options = {
			'title': dep.name,
			//'width':700,
			//'height':400,
			hAxis: { title: time.name, slantedText:true, slantedTextAngle:90,  },
			vAxis: {
					title: dep.name,
					gridlines: {
						color: 'transparent'
					}},

			trendlines: { 5: {} },
			//legend: 'none'
			/*
			vAxis: {
				0: { title: indep.name },
				1: { title: indep.name }
			},
			
			series: {
				0: { type: "line", targetAxisIndex: 0 },
				1: { type: "bars", targetAxisIndex: 1}
			}*/
		};

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.LineChart(document.getElementById( "trendChart" ));
		//chartObj.push( new google.visualization.LineChart(document.getElementById( variable.name )) );
		chart.draw(data, options);
}

///// Load data from php
function trendCreateChartContainers() {

	//var time = trendChartData.time;
	//var dep = trendChartData.dependent;
	var outerChartDiv = document.createElement('div');

	// Create empty divs for each chart
	/*
	trendChartData.independent.forEach( function(indep) {
		var chartDiv = document.createElement('div');
		chartDiv.id = indep.name;
		chartDiv.className  = "vizChart";
		outerChartDiv.appendChild(chartDiv);
	}); */
	
	var chartDiv = document.createElement('div');
	chartDiv.id = "trendChart";
	chartDiv.className  = "vizChart";
	outerChartDiv.appendChild(chartDiv);

	document.getElementById('trendChartContainer').appendChild(outerChartDiv);			
	trendDrawChart(trendChartData.time, trendChartData.dependent);
	
	/*
	// Create individual charts
	trendChartData.independent.forEach( function(indep) {
		drawChart(indep);
	});
	*/

}

function trendDataLoaded(data) {
	console.log(data);
	trendChartData = data;
	// Set a callback to run when the Google Visualization API is loaded.
	//google.setOnLoadCallback(trendCreateChartContainers);
	trendCreateChartContainers();
}


/////////////////// INIT
// Load the Visualization API and the piechart package.
//google.load('visualization', '1.0', {'packages':['corechart']});
$( document ).ready(function() {

	//var trendChartData = <?php echo json_encode( getData() ); ?>;
	$.get( "viz/TrendChart/Trend.php", { edaId: edaId }, trendDataLoaded, "json" ).fail( function(err) { console.log("ERROR"); console.log(err); });

});