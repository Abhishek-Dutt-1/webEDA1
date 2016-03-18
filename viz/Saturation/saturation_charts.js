// create a top level data object
var chartData = {};
/////////////////// INIT
$( document ).ready(function() {

	//var meanDiffChartData = <?php echo json_encode( getData() ); ?>;
	console.log("edaId: " + edaId);
	console.log("modelId: " + modelId);
	
	$.get( "viz/Contrib/Contrib.php", { dataType: 'saturationCurve', edaId: edaId, modelId: modelId, projectId: projectId }, contribDataLoaded, "json" ).fail( function(err) { 
		console.log("Saturation Curve ERROR");
		console.log(err); 
	});

	// attach event listener to updateSensitivityChart href
	$("#updateSaturationChart").on('click', updateSaturationChartsData);
	
	$('.popbox').popbox({
		'open'          : '.popboxOpen',
		'box'           : '.popboxBox',
		'arrow'         : '.popboxArrow',
		'arrow_border'  : '.popboxArrow-border',
		'close'         : '.popboxClose'
	});
	
	
});

function contribDataLoaded( data ) {

	chartData = data;
	// Create inputs
	createSaturationInputs();
	// Create empty divs for Saturation Charts;
	createSaturationChartsDivs();
	// Update saturation data in chartData obj
	updateSaturationChartsData();

	/*
	//// Create Contrib series charts
	contribSeriesChart();
	//// Create Average Contribution series column chart
	averageContributionCharts();
	//// Create initial Average Sensitivity column chart
	createCPRPinputs();
	updateSensitivityCharts();
	*/
}

// Create html Inputs for saturation
function createSaturationInputs() {
	
	var inputDivPos = document.createElement('div');
	var inputDivNeg = document.createElement('div');
	var widgetDiv = document.createElement('div');
	// Create inputs for start step end
	// START
	var label = document.createElement('label');
	label.setAttribute("for", "START" );
	label.innerHTML = "Start";
	var input = document.createElement('input');
	input.type = "text";
	input.value = 0;
	input.id = "START";
	input.className  = "saturationInput";
	
	widgetDiv.appendChild(label);
	widgetDiv.appendChild(input);
	// STEP
	label = document.createElement('label');
	label.setAttribute("for", "INCREMENT" );
	label.innerHTML = "Increment";
	input = document.createElement('input');
	input.type = "text";
	input.value = 50;
	input.id = "INCREMENT";
	input.className  = "saturationInput";
	
	widgetDiv.appendChild(label);
	widgetDiv.appendChild(input);
	// END
	label = document.createElement('label');
	label.setAttribute("for", "END" );
	label.innerHTML = "End";
	input = document.createElement('input');
	input.type = "text";
	input.value = 1000;
	input.id = "END";
	input.className  = "saturationInput";
	
	widgetDiv.appendChild(label);
	widgetDiv.appendChild(input);
	
	document.getElementById('saturationInputContainer').appendChild(widgetDiv);
	
	// Create empty divs for each chart
	chartData.modelData.independent.forEach( function(indep) {

		var label = document.createElement('label');
		label.setAttribute("for", indep.name.replace( /[^\w\d]/g, "_") + "_BaseValue" );
		label.innerHTML = indep.name + " Base Value";
		
		var input = document.createElement('input');
		input.type = "text";
		input.value = 0;
		input.id = indep.name.replace( /[^\w\d]/g, "_") + "_BaseValue";
		input.className  = "sensitivityInput";
	
		// Put +ve variables first to match the sensitivity charts			
		if(indep.coef > 0) {
			inputDivPos.appendChild(label);
			inputDivPos.appendChild(input);
		} else {
			inputDivNeg.appendChild(label);
			inputDivNeg.appendChild(input);
		}
	});
	
	document.getElementById('saturationInputContainer').appendChild(inputDivPos);
	document.getElementById('saturationInputContainer').appendChild(inputDivNeg);
}

// Create empty divs for charts
function createSaturationChartsDivs() {
	
	var outerChartDivPos = document.createElement('div');
	var outerChartDivNeg = document.createElement('div');
	// Create empty divs for each chart
	chartData.modelData.independent.forEach( function(indep) {
		
		var chartDiv = document.createElement('div');
		chartDiv.id = indep.name.replace( /[^\w\d]/g, "_") + "_SatChart";
		chartDiv.className  = "saturationChart";
		// Put +ve variables first to match the sensitivity charts			
		if(indep.coef > 0) {
			outerChartDivPos.appendChild(chartDiv);
		} else {
			outerChartDivNeg.appendChild(chartDiv);
		}
	});
	
	document.getElementById('saturationChartsContainer').appendChild(outerChartDivPos);
	document.getElementById('saturationChartsContainer').appendChild(outerChartDivNeg);
}

// Get user input base value
function getSaturationBaseValue(indepName) {
	var baseValue = 0;
	chartData.modelData.independent.forEach( function(indep) {
		if(indep.name == indepName) {
			baseValue = document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_BaseValue" ).value;
			if( !(!isNaN(parseFloat(baseValue)) && isFinite(baseValue)) ) {
				document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_BaseValue" ).value = 0;
				console.log("Base Value IsNaN");
			}
			baseValue = document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_BaseValue" ).value;
		}
	});
	//console.log(baseValue);
	return baseValue;
}

// Calculate actual saturation data for Saturation Curve
function calcSaturationSeries(indepName) {
	
	var contribSum = [];
	var i = 0;
	var start = parseFloat( document.getElementById( "START" ).value );
	var inc = parseFloat( document.getElementById( "INCREMENT" ).value );
	var end = parseFloat( document.getElementById( "END" ).value );
	var satData = { name: '', GRP: [], response: [], responseMax: 0, responseMin: 0, diff: [], diffMax: 0, diffMin:0};
	satData.name = indepName;
	
	for(i = start; i <= end; i = i + inc) {
		var co = [];
		var power = [];
		var contrib = [];

		chartData.modelData.independent.forEach( function(indep, index, independent) {
			if(indep.name == indepName) {
				// For current variable whose saturation is to be charted
				co.push( i + ( indep.coSeries[indep.coSeries.length - 1] * indep.co ) );
				power.push( Math.pow( co[co.length - 1], indep.power ) );
				contrib.push( power[power.length - 1] * indep.coef );	
			}
			else {
				// For other variables base value is from user Input
				co.push( getSaturationBaseValue(indep.name) + ( indep.coSeries[indep.coSeries.length - 1] * indep.co ) );
				power.push( Math.pow( co[co.length - 1], indep.power ) );
				contrib.push( power[power.length - 1] * indep.coef );
			}
		});
		satData.GRP.push(i);
		// yPredBase = Intercept + sum of all contributions
		satData.response.push( chartData.modelData.intercept.coef + contrib.reduce(function(pv, cv) { return pv + cv; }, 0) );

	}
	// Also update Diff array
	satData.diff.push( null );
	for(i = 1; i < satData.GRP.length; i++) {
		satData.diff.push( satData.response[i] - satData.response[i-1] );
	}
	
	satData.responseMax = Math.max.apply(Math, satData.response);
	satData.responseMin = Math.min.apply(Math, satData.response);
	satData.diffMax = Math.max.apply(Math, satData.diff);
	satData.diffMin = Math.min.apply(Math, satData.diff);

	//console.log(satData);
	return satData;
	
}

// Create the actual charts
function updateSaturationChartsData() {

	//////////////////// calculate Base Value for Saturation Curve
	chartData.modelData.independent.forEach( function(ind, index, independent) {
		independent[index]['SaturaionCurve'] = calcSaturationSeries(ind.name);
	});
	console.log( chartData );
	// Draw the actual caharts
	drawSaturationCharts();
}

// Draw the actual charts
function drawSaturationCharts() {

	chartData.modelData.independent.forEach( function(indep) {
		
		// Dont do competition chart
		if(indep.coef > 0) {
			$('#'+indep.name.replace( /[^\w\d]/g, "_") + "_SatChart" ).highcharts({
				title: {
					text: indep.name,
					align: 'left',
					x: 70 //center
				},
				legend: {
					enabled: true,
					floating: true,
					verticalAlign: 'top',
					align:'right',
					x: -70
				},
				xAxis: {
					title: { text: indep.name },
					categories: indep.SaturaionCurve.GRP
				},
				yAxis: [{
							title: {
								text: chartData.modelData.dependent.name
							},
							max: indep.SaturaionCurve.responseMax,
							min: indep.SaturaionCurve.responseMin,
						},
						{
							title: {
								text: "Change"
							},
							opposite: true,
							//max: indep.SaturaionCurve.diffMax,
							//min: indep.SaturaionCurve.diffMin
						}],
				tooltip: {
					//enabled: false
					shared: true
				},
				series: [{
					name: chartData.modelData.dependent.name,
					data: indep.SaturaionCurve.response,
					color: chartData.modelData.dependent.color
				}, {
					name: 'Change',
					data: indep.SaturaionCurve.diff,
					yAxis: 1
				}],
				credits: false
			});
		}
	});
}





////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//// Create Contrib series charts
function contribSeriesChart( ) {

	var data = chartData;
	console.log( data );
	console.log("yAxisMax: " +  data.modelData.yAxisMax );
	data.modelData.yAxisMax = data.modelData.yAxisMax * 1.2;
	var seriesData = [];
	var seriesDataNeg = [];
	
	seriesData.push( {name: data.modelData.dependent.name, data: data.modelData.dependent.data,	type: 'line', zIndex: 2});
	data.modelData.independent.forEach( function(elem) {
		if( elem.coef < 0) {
			seriesDataNeg.push( { name: elem.name, data: elem.contribSeries, yAxis: 1 } );
		} else {
			seriesData.push( { name: elem.name, data: elem.contribSeries } );
		}
	});
	seriesData.push( {name: data.modelData.intercept.name, data: data.modelData.intercept.contribSeries});
	seriesData = seriesData.concat(seriesDataNeg);
	
    $('#saturationChartsContainer').highcharts({
        chart: {
            type: 'area',
			height: 500,
			zoomType: 'x'
        },
        title: {
            text: data.modelData.modelName
        },
        xAxis: {
            categories: data.modelData.time.data,
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            },
			labels: { rotation: -45, maxStaggerLines: 0, step: 3 }
        },
        yAxis: [{
			gridLineColor: 'transparent',
            title: {
                text: data.modelData.dependent.name
            },
			// min: 0, Intercept can be negative in extreme cases
			max: data.modelData.yAxisMax
        },{ // Secondary yAxis
			gridLineColor: 'transparent',
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true,
			max: 0,
			min: -data.modelData.yAxisMax
        }],
        tooltip: {
            shared: true,
			enabled: false
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666',
					enabled: false
                }
            }
        },
        series: seriesData,
		credits: false
    });
}

//// Create Average Contribution series column chart
function averageContributionCharts( ) {
	
	var data = chartData;
	var seriesData = [];
	var seriesDataNeg = [];
	var xAxisData = [];
	seriesData.push( [ data.modelData.intercept.name, data.modelData.intercept.averageContribution ] );
	data.modelData.independent.forEach( function(elem) {
		//seriesData.push( [ elem.name, elem.averageContribution ] );
		if( elem.coef < 0) {
			seriesDataNeg.push( [ elem.name, elem.averageContribution ] );
		} else {
			seriesData.push( [ elem.name, elem.averageContribution ] );
		}
	});
	seriesData = seriesData.concat(seriesDataNeg);
	xAxisData.push( data.modelData.intercept.name );
	data.modelData.independent.forEach( function(elem) {
		xAxisData.push( elem.name );
	});
	
    $('#avContributionChart').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: 'Average Contribution',
				style: {
					display: 'none'
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				enabled: false
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				gridLineColor: 'transparent',
				title: {
					//text: 'Average Contribution (%)'
					text: null
				},
				labels: {
					enabled: false
				}
			},
			credits: {
				enabled: false
			},
			series: [{
				name: "Average Contribution",
				data: seriesData,
				dataLabels: {
					enabled: true,
					/*
					rotation: -90,
					color: '#FFFFFF',
					align: 'right',
					x: 4,
					y: 10,
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif',
						textShadow: '0 0 3px black'
					},
					*/
					formatter: function() { return Math.round(this.y * 100,0) + '%'; }
				}
			}]
					
		});
}

// Create average sensitivity interface
function averageSensitivityCharts( )
{
	var data = chartData;
	var seriesData = [];
	var seriesDataNeg = [];

	data.modelData.independent.forEach( function(elem) {
		//seriesData.push( [ elem.name, elem.averageContribution ] );
		if( elem.coef < 0) {
			seriesDataNeg.push( [ elem.name, elem.sensitivity.value ] );
		} else {
			seriesData.push( [ elem.name, elem.sensitivity.value ] );
		}
	});
	seriesData = seriesData.concat(seriesDataNeg);
	
    $('#senstivityChart').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: 'Average Sensitivity',
				style: {
					display: 'none'
				}
			},
			legend: {
				enabled: false
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			tooltip: {
				enabled: false
			},
			yAxis: {
				gridLineColor: 'transparent',
				title: {
					//text: 'Sensitivity (%)'
					text: null
				},
				labels: {
					enabled: false
				}
			},
			credits: {
				enabled: false
			},
			series: [{
				name: "Average Contribution",
				data: seriesData,
				dataLabels: {
					enabled: true,
					/*
					rotation: -90,
					color: '#FFFFFF',
					align: 'right',
					x: 4,
					y: 10,
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif',
						textShadow: '0 0 3px black'
					},
					*/
					formatter: function() { return parseFloat(this.y * 100).toFixed(2) + '%'; }
				}
			}]
					
		});
}

// Create inputs for CPRP
function createCPRPinputs() {

	var outerChartDivPos = document.createElement('div');
	var outerChartDivNeg = document.createElement('div');
	// Create empty divs for each chart
	chartData.modelData.independent.forEach( function(indep) {
		
		// Put +ve variables first to match the sensitivity charts

			var label = document.createElement('label');
			label.setAttribute("for", indep.name.replace( /[^\w\d]/g, "_") + "_CPRP" );
			label.innerHTML = indep.name + " CPRP";
			
			var input = document.createElement('input');
			input.type = "text";
			input.value = 1;
			input.id = indep.name.replace( /[^\w\d]/g, "_") + "_CPRP";
			input.className  = "cprpInput";
			
		if(indep.coef > 0) {
			outerChartDivPos.appendChild(label);
			outerChartDivPos.appendChild(input);
		} else {
			outerChartDivNeg.appendChild(label);
			outerChartDivNeg.appendChild(input);
		}
	});
	
	document.getElementById('cprpInputContainer').appendChild(outerChartDivPos);
	document.getElementById('cprpInputContainer').appendChild(outerChartDivNeg);
}

// get Investment / CPRP for each var
function getInvestmentForVariable(indepName) {
	
	var inv = 0;
	var cprp = 1;
	chartData.modelData.independent.forEach( function(indep) {
		if(indep.name == indepName) {
			// GRP == Investment/CPRP
			console.log("GRP for: " + indepName);
			//console.log( parseFloat(document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_CPRP" ).value) );
			//console.log( parseFloat( document.getElementById('investment').value ) );
			//console.log(parseFloat( document.getElementById('investment').value )/ parseFloat(document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_CPRP" ).value));
			cprp = document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_CPRP" ).value;
			if( !(!isNaN(parseFloat(cprp)) && isFinite(cprp)) || (cprp == 0) ) {
				document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_CPRP" ).value = 1;
				console.log("ISNAN");
			}
			cprp = document.getElementById( indep.name.replace( /[^\w\d]/g, "_") + "_CPRP" ).value;
			inv = parseFloat( document.getElementById('investment').value )/ cprp;
		}
	});
	console.log( inv );
	return inv;
}

// Handle Button Click
function updateSensitivityCharts()
{
	// use top level chartData since it modifies it
	console.log( chartData );
	//console.log( document.getElementById('investment').value );
	//console.log( document.getElementById('periodAveraged').value );
	var periodAveraged = parseInt( document.getElementById('periodAveraged').value );
	var investment = parseFloat( document.getElementById('investment').value );
	var i = 0;
	var contribSum = [];
	
	var contribSumBase, contribSumInvestment;
	
	//////////////////// calculate Base Value for Sensitivity
	// Contrib series for intercept is constant == coeff
	contribSum.push( chartData.modelData.intercept.coef * periodAveraged );
	// AdStocked Contributions from independent variables
	chartData.modelData.independent.forEach( function(indep, index, independent) {
		var coSeries = [];
		var powerSeries = [];
		var contribSeries = [];
		
		for(i = 0; i < periodAveraged; i++) {
			if( i == 0) {
				coSeries.push( 0 + ( indep.coSeries[indep.coSeries.length - 1] * indep.co ) );
				powerSeries.push( Math.pow( coSeries[coSeries.length - 1], indep.power ) );
				contribSeries.push( powerSeries[powerSeries.length - 1] * indep.coef );
			} else {
				coSeries.push( 0 + ( coSeries[coSeries.length - 1] * indep.co ) );
				powerSeries.push( Math.pow( coSeries[coSeries.length - 1], indep.power) );
				contribSeries.push( powerSeries[powerSeries.length - 1] * indep.coef );
			}
		};
		//console.log( indep.name );
		//console.log( powerSeries );
		// contribSum = sum of all contributions
		contribSum.push( contribSeries.reduce(function(pv, cv) { return pv + cv; }, 0) );
		independent[index]['sensitivity'] = {};
		independent[index].sensitivity.contribSumBase = contribSeries.reduce(function(pv, cv) { return pv + cv; }, 0);
	});
	//console.log(contribSum);
	contribSumBase =  contribSum.reduce(function(pv, cv) { return pv + cv; }, 0)
	contribSum = [];
	
	//////////////////////// calcluateInvestmentValue for each variable
	// Contrib series for intercept is constant == coeff 
	contribSum.push( chartData.modelData.intercept.coef * periodAveraged );
	// AdStocked Contributions from independent variables
	chartData.modelData.independent.forEach( function(indep, index, independent) {
		var coSeries = [];
		var powerSeries = [];
		var contribSeries = [];
		
		for(i = 0; i < periodAveraged; i++) {
			if( i == 0) {
				//coSeries.push( investment + ( indep.coSeries[indep.coSeries.length - 1] * indep.co ) );
				coSeries.push( getInvestmentForVariable(indep.name) + ( indep.coSeries[indep.coSeries.length - 1] * indep.co ) );
				powerSeries.push( Math.pow( coSeries[coSeries.length - 1], indep.power ) );
				contribSeries.push( powerSeries[powerSeries.length - 1] * indep.coef );
			} else {
				coSeries.push( 0 + ( coSeries[coSeries.length - 1] * indep.co ) );
				powerSeries.push( Math.pow( coSeries[coSeries.length - 1], indep.power) );
				contribSeries.push( powerSeries[powerSeries.length - 1] * indep.coef );
			}
		};
		//console.log( indep.name );
		//console.log( coSeries );
		// contribSum = sum of all contributions
		contribSum.push( contribSeries.reduce(function(pv, cv) { return pv + cv; }, 0) );
		independent[index].sensitivity.contribSumInvestment = contribSeries.reduce(function(pv, cv) { return pv + cv; }, 0);
	});
	//console.log(contribSum);
	
	// Calc actual sensitivity
	var baseValue = chartData.modelData.intercept.coef * periodAveraged;
	chartData.modelData.independent.forEach( function(indep, index, independent) {
		baseValue += indep.sensitivity.contribSumBase;
	});
	
	var tmpYPred = 0;
	chartData.modelData.independent.forEach( function(indep, index, independent) {
		// yPred on investment
		tmpYPred = baseValue - indep.sensitivity.contribSumBase + indep.sensitivity.contribSumInvestment;
		independent[index].sensitivity.value = (tmpYPred/baseValue)-1;
	});
	//console.log(baseValue);
	console.log( chartData );

	// create the chart
	averageSensitivityCharts( chartData );
	return false;
}