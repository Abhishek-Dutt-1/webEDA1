// create a top level data object
var chartData = {};
// top level simData used later
var simData = [];
/////////////////// INIT
$( document ).ready(function() {

	//var meanDiffChartData = <?php echo json_encode( getData() ); ?>;
	console.log("edaId: " + edaId);
	console.log("modelId: " + modelId);
	
	$.get( "viz/Contrib/Contrib.php", { dataType: 'simlutionData', edaId: edaId, modelId: modelId, projectId: projectId }, simulationDataLoaded, "json" ).fail( function(err) { 
		console.log("Contrib ERROR");
		console.log(err); 
	});

	// attach event listener to updateSensitivityChart href
	$("#updateSimulationChart").on('click', refreshSimulationChart);
	//$("#updateSimulationChart").on('click', refreshSimulationChart);
	
	// Attach popbox
	$('.popbox').popbox({
		'open'          : '.popboxOpen',
		'box'           : '.popboxBox',
		'arrow'         : '.popboxArrow',
		'arrow_border'  : '.popboxArrow-border',
		'close'         : '.popboxClose'
	});
	
	// Make table editable
	//$("td").dblclick(function () { 
	$("#simulationTable").on('click', 'td.editableCell', function () {
		var OriginalContent = $(this).text(); 
		$(this).addClass("cellEditing"); 
		$(this).html("<input type='text' class='tableCellEdit' value='" + OriginalContent + "' />"); 
		$(this).children().first().focus(); 
		$(this).children().first().keypress(function (e) { 
			if (e.which == 13) { 
				var newContent = $(this).val(); 
				$(this).parent().text(newContent); 
				$(this).parent().removeClass("cellEditing");
                cellEditUpdate();
			} 
		});
		$(this).children().first().blur(function(){
			//$(this).parent().text(OriginalContent);
			//$(this).parent().removeClass("cellEditing");
			//cellEditUpdate();
			var newContent = $(this).val(); 
			$(this).parent().text(newContent); 
			$(this).parent().removeClass("cellEditing");
			cellEditUpdate();
		}); 
	}); 

});

// Initial Load
function simulationDataLoaded(data) {

	chartData = data;
	createInputTable();
	calcDependentValues();
	createSimulationChart();
}

// Button Pressed Load
function refreshSimulationChart() {
	createInputTable()
	calcDependentValues();
	createSimulationChart();
	return false;
}

// Table cell edited
function cellEditUpdate() {
	calcDependentValues();
	createSimulationChart();
}

// Create the table for inputs
function createInputTable() {

	var elem = document.getElementById("tableDiv");
	if(elem) elem.parentElement.removeChild(elem);
	
	var tableDiv = document.createElement('div');
	tableDiv.id = "tableDiv";
	
	// Create Table
	var table  = document.createElement('table');
	table.className = "editableTable";
    table.id = "simTable";
    //table.style.width='400px';

	///////// Table Header
	var tHead = table.createTHead();
	tHead.id = "editableTableHeader";
	var row = tHead.insertRow(0);
	// Time
	var cell = row.insertCell(0);
	cell.innerHTML = chartData.modelData.time.name;
	// Dependent
	cell = row.insertCell(1);
	cell.innerHTML = chartData.modelData.dependent.name;
	// Independents -- Positives
	chartData.modelData.independent.forEach( function(indep) {
		if(indep.coef >= 0) {
			cell = row.insertCell(-1);
			cell.innerHTML = indep.name;
		}
	});
	// Independents -- Negatives
	chartData.modelData.independent.forEach( function(indep) {
		if(indep.coef < 0) {
			cell = row.insertCell(-1);
			cell.innerHTML = indep.name;
		}
	});
	////////// End table header
	var numPeriodSimulated = document.getElementById('numPeriodSimulated').value;
	console.log(numPeriodSimulated);
	var tBody = table.createTBody();
	var tr, td;
	var keepZero = document.querySelector('input[name="keepZero"]:checked').value ;
	keepZero = ( keepZero == "true" ) ? true : false;
	console.log(keepZero);
	
	for(var i = 0; i < numPeriodSimulated; i++) {
		tr = tBody.insertRow(-1);
		// Time
		td = tr.insertCell(0);
		td.appendChild(document.createTextNode( chartData.modelData.time.data[chartData.modelData.time.data.length - 1] + " + " + parseInt(i+1) ))
		// Dep
		td = tr.insertCell(1);
		td.appendChild( document.createTextNode( '-' ) );
		// Pos
		chartData.modelData.independent.forEach( function(indep) {
			if(indep.coef >= 0) {
				td = tr.insertCell(-1);
				td.className = "editableCell";
				if(keepZero)
					td.appendChild( document.createTextNode(0) );
				else
					td.appendChild( document.createTextNode( indep.data[indep.data.length - numPeriodSimulated + i] ) );
			}
		});
		// Neg
		chartData.modelData.independent.forEach( function(indep) {
			if(indep.coef < 0) {
				td = tr.insertCell(-1);
				td.className = "editableCell";
				if(keepZero)
					td.appendChild( document.createTextNode(0) );
				else
					td.appendChild( document.createTextNode( indep.data[indep.data.length - numPeriodSimulated + i] ) );
			}
		});
	}
	
	tableDiv.appendChild(table);
    document.getElementById('simulationTable').appendChild(tableDiv);
}

// Update dependent values based on the table
function calcDependentValues() {

    var elem = document.getElementById("simTable");
	if(!elem) return; 

    simData = [];
	var i, j;
	
	// Load user input data from html table
    var table = document.getElementById("simTable");
    for (var i = 0, row; row = table.rows[i]; i++) {

        for (var j = 0, col; col = row.cells[j]; j++) {
            // First row is header row
            if (i == 0) {
                simData.push( { name: col.innerHTML, data: [] } );
            } else {
            // Rest rows are data rows
                simData[j].data.push( col.innerHTML || 0);
            }
        }
    } 

	var numPeriodSimulated = document.getElementById('numPeriodSimulated').value;

	// Clear yPred col
    simData[1].data = [];
    
	var tmpArray = new Array(parseInt(numPeriodSimulated)+1).join('0').split('').map(parseFloat)
	
	chartData.modelData.independent.forEach( function(indep, index, independent) {
		var coSeries = [];
		var powerSeries = [];
		var contribSeries = [];
		for (i = 0; i < numPeriodSimulated; i++) {
		
			if (i == 0) {
				//coSeries.push( investment + ( indep.coSeries[indep.coSeries.length - 1] * indep.co ) );
				coSeries.push( getInputDataForVariable(indep.name, i) + ( indep.coSeries[indep.coSeries.length - 1] * indep.co ) );
				powerSeries.push( Math.pow( coSeries[coSeries.length - 1], indep.power ) );
				contribSeries.push( powerSeries[powerSeries.length - 1] * indep.coef );
			} else {
				coSeries.push( getInputDataForVariable(indep.name, i) + ( coSeries[coSeries.length - 1] * indep.co ) );
				powerSeries.push( Math.pow( coSeries[coSeries.length - 1], indep.power) );
				contribSeries.push( powerSeries[powerSeries.length - 1] * indep.coef );
			}
		};
		//console.log(contribSeries);
		// Store contrib for each variable one at a time
		for (j = 0; j < numPeriodSimulated; j++) {
			tmpArray[j] = tmpArray[j] + contribSeries[j];
		};
        // simData[1] must always be the dependent
        // dependent = intercept + sum of all contribSeries
        //simData[1].data.push( chartData.modelData.intercept.coef + contribSeries.reduce(function(pv, cv) { return pv + cv; }, 0) );
	});

	// Add intercept to the contrib (yPred)
	for (j = 0; j < numPeriodSimulated; j++) {
		tmpArray[j] = tmpArray[j] + chartData.modelData.intercept.contribSeries[j];
	};
	simData[1].data = tmpArray;
	//console.log(tmpArray);
    //console.log( simData );
	
    // Update table with calculated yPred
    var table = document.getElementById("simTable");
    for (var i = 0, row; row = table.rows[i]; i++) {

        for (var j = 0, col; col = row.cells[j]; j++) {
            // First row is header row
            if (i == 0) {

            } else {
            // Rest rows are data rows
                if (j == 1) {
                    col.innerHTML = simData[1].data[i-1];
                }
            }
        }
    } 

    // Returns the data from the table for a given variable and position
    // Local function
    function getInputDataForVariable(indepName, i) {
        return parseFloat( simData.filter( function(el) { return el.name == indepName; })[0].data[i] );
    }
}

// Update the Simuation Chart
function createSimulationChart() {
    
    var xAxis = chartData.modelData.time.data.concat( simData[0].data );
    var actualDependent = chartData.modelData.dependent.data.concat( simData[1].data.map( function(e) { return null; } )  );
    var simulatedDependent = chartData.modelData.dependent.data.map( function(e) { return null; } ).concat( simData[1].data );
	simulatedDependent[simulatedDependent.length - simData[1].data.length -1] = chartData.modelData.dependent.data[chartData.modelData.dependent.data.length - 1];
	//console.log(  actualDependent );
	//console.log( chartData.modelData.dependent.data.length );
	
    $('#simulationChart').highcharts({
        title: {
            text: chartData.modelData.dependent.name,
            align: 'left',
            x: 60
        },
        xAxis: {
            categories: xAxis,
			labels: { rotation: -45, maxStaggerLines: 0, step: 2 }
        },
        yAxis: {
            title: {
                text: chartData.modelData.dependent.name
            },
        },
        legend: {
            align: 'right',
            verticalAlign: 'top',
            floating: true
        },
        series: [{
            name: chartData.modelData.dependent.name,
            data: actualDependent,
			color: chartData.modelData.dependent.color
        }, {
            name: 'Simulation',
            data: simulatedDependent 
        }],
        credits: false
    });

}
