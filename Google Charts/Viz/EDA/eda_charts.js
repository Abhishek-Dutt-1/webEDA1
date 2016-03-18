var edaChartData;
var charObj = []
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart(time, dep, indep) {

	// Create the data table.
	var i = 0;
	var dataArray = [];
	for(i=0; i < time.data.length; i++) {
		dataArray.push( [time.data[i], dep.data[i], indep.data[i]] );
	}

	var data = new google.visualization.DataTable();
		data.addColumn('string', time.name);
		data.addColumn('number', dep.name);
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
			'title': dep.name + " vs. " + indep.name,
			//'width':400,
			//'height':300,
			hAxis: { title: time.name },
			vAxes: {
				0: { title: dep.name },
				1: { title: indep.name }
			},
			series: {
				0: { type: "line", targetAxisIndex: 0 },
				1: { type: "bars", targetAxisIndex: 1}
			},
			legend: { position: "bottom" }
			
		};

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ComboChart(document.getElementById( indep.name ));
		//chartObj.push( new google.visualization.LineChart(document.getElementById( variable.name )) );
		chart.draw(data, options);
}

///// Load data from php
function createChartContainers() {

	var time = edaChartData.time;
	var dep = edaChartData.dependent;
	var outerChartDiv = document.createElement('div');

	// Create empty divs for each chart
	edaChartData.independent.forEach( function(indep) {
		var chartDiv = document.createElement('div');
		chartDiv.id = indep.name;
		chartDiv.className  = "edaChart";
		outerChartDiv.appendChild(chartDiv);
	});

	document.getElementById('edaChartContainer').appendChild(outerChartDiv);			
	
	// Create individual charts
	edaChartData.independent.forEach( function(indep) {
		drawChart(time, dep, indep);
	});

}

function dataLoaded(data) {
	console.log(data);
	edaChartData = data;
	// Set a callback to run when the Google Visualization API is loaded.
	//google.setOnLoadCallback(createChartContainers);
	createChartContainers();
}

/////////////////// INIT
// Load the Visualization API and the piechart package.
//google.load('visualization', '1.0', {'packages':['corechart']});
$( document ).ready(function() {

	//var edaChartData = <?php echo json_encode( getData() ); ?>;
	$.get( "viz/eda/EDA.php", { edaId: edaId }, dataLoaded, "json" ).fail( function(err) { console.log("ERROR"); console.log(err); });

});