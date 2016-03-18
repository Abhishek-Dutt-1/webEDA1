// create a top level data object
var chartData = {};
/////////////////// INIT
$( document ).ready(function() {

	//var meanDiffChartData = <?php echo json_encode( getData() ); ?>;
	console.log("edaId: " + edaId);
	console.log("modelId: " + modelId);
	
	$.get( "viz/Contrib/Contrib.php", { dataType: 'AvP', edaId: edaId, modelId: modelId, projectId: projectId }, avpDataLoaded, "json" ).fail( function(err) { 
		console.log("Contrib ERROR");
		console.log(err); 
	});

	// attach event listener to updateSensitivityChart href
	$("#updateSensitivityChart").on('click', updateSensitivityCharts);

});

function avpDataLoaded( data ) {
	chartData = data;
	console.log(data);
	printModelStats();
	createAvPChart();
}

// Print stats such as Rsq etc
function printModelStats() {
	
	//var tmpOuterDiv = document.createElement('div');
	// Rsq
	var tmpDiv = document.createElement('div');
	tmpDiv.className = "avpModelStats";
	tmpDiv.id = "Rsquare";
	tmpDiv.innerHTML = Math.round(parseFloat(chartData.modelData.rsquare) * 100) + '%';
	//tmpOuterDiv.appendChild(tmpDiv);
	document.getElementById('rsq').appendChild(tmpDiv);
		
	// Adj Rsq
	tmpDiv = document.createElement('div');
	tmpDiv.className = "avpModelStats";
	tmpDiv.id = "adjRsquare";
	tmpDiv.innerHTML = Math.round(parseFloat(chartData.modelData.adjr2) * 100) + '%';
	//tmpOuterDiv.appendChild(tmpDiv);
	document.getElementById('adjrsq').appendChild(tmpDiv);
		
}

// Create avp chart
function createAvPChart() {

    $('#avpChart').highcharts({
        title: {
            text: null //'Actual vs Predicted',
        },
        xAxis: {
            categories: chartData.modelData.time.data,
			labels: { rotation: -45, maxStaggerLines: 0, step: 2 }
        },
        yAxis: {
            title: {
                text: chartData.modelData.dependent.name
            },
        },
        tooltip: {
            shared: true
        },
        legend: {
            //layout: 'vertical',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0,
			floating: false
        },
        series: [{
					name: chartData.modelData.dependent.name,
					data: chartData.modelData.dependent.data,
					color: chartData.modelData.dependent.color
				}, {
					name: chartData.modelData.predicted.name,
					data: chartData.modelData.predicted.data
				}],
		credits: false
    });

}

////////////////////////////////////////////////////////////////////////////////////////////

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
	
    $('#contribSeriesChart').highcharts({
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