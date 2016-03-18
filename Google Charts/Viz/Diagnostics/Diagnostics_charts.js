var diagnosticsChartData;
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function diagnosticsDrawChart(indep) {

	// Create the data table.
	var i = 0;
	var dataArray = [];
	for(i=0; i < indep.data.length; i++) {
		//dataArray.push( [time.data[i], dep.data[i], indep.data[i]] );
		dataArray.push( [indep.binsTxt[i], indep.data[i]] );
	}

	var data = new google.visualization.DataTable();
//		data.addColumn('string', time.name);
		data.addColumn('string', indep.name);
		data.addColumn('number', indep.name);
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
			'title': indep.name + " vs. " + indep.name,
			//'width':400,
			//'height':300,
			hAxis: { title: "Bins", slantedText:true, slantedTextAngle:90,  },
			vAxis: { title: indep.name + " Freq"},
			chartArea: {
				//top: 10,
				height: '45%' 
			},
//			trendlines: { 0: {} },
			legend: 'none'
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
		var chart = new google.visualization.ColumnChart(document.getElementById( indep.name ));
		//chartObj.push( new google.visualization.LineChart(document.getElementById( variable.name )) );
		chart.draw(data, options);
}

///// Load data from php
function diagnosticsCreateChartContainers() {

	//var time = diagnosticsChartData.time;
	//var dep = diagnosticsChartData.dependent;
	var outerChartDiv = document.createElement('div');

	// Create empty divs for each chart
	diagnosticsChartData.independent.forEach( function(indep) {
		var chartDiv = document.createElement('div');
		chartDiv.id = indep.name;
		chartDiv.className  = "diagnosticsChart";
		outerChartDiv.appendChild(chartDiv);
	});

	document.getElementById('diagnosticsChartContainer').appendChild(outerChartDiv);			
	
	// Create individual charts
	diagnosticsChartData.independent.forEach( function(indep) {
		diagnosticsDrawChart(indep);
	});

}

function diagnosticsDataLoaded(data) {
	console.log(data);
	diagnosticsChartData = data;
	// Set a callback to run when the Google Visualization API is loaded.
	//google.setOnLoadCallback(diagnosticsCreateChartContainers);
	diagnosticsCreateChartContainers();
}


/////////////////// INIT
// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});
$( document ).ready(function() {

	//var diagnosticsChartData = <?php echo json_encode( getData() ); ?>;
	$.get( "viz/Diagnostics/Diagnostics.php", { edaId: edaId }, diagnosticsDataLoaded, "json" ).fail( function(err) { console.log("ERROR"); console.log(err); });

});