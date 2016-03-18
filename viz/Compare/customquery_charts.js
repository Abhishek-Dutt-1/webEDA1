var allData = {};
//////
function compareDataLoaded(data) {
	console.log(data);
	var brands = [];
	var kpis = [];

	brands = getAllUniqueBrands(data);
	kpis = getAllUniqueKPIs(data);
	/*
	allData = {brands: []};
	brands.forEach( function(brand) {
		allData.brands.push({
					brand: brand,
					KPI: data.KPI.filter( function(e) { return e.Brand == brand; } ),
					DRIVER: data.DRIVER.filter( function(e) { return e.Brand == brand; } )
		});
	});
	*/
	allData = {brands: []};
	var brandExists = false;
	var kpiExists = false;
	var i, j;
	brands.forEach( function(brand) {
		kpis.forEach( function(kpi) {
			data.KPI.forEach( function(dataKpi) {
				if(dataKpi.Brand == brand && dataKpi.Variable_Type == kpi) {
					// Find and update
					for (i = 0; i < allData.brands.length; i++) {
						for (j = 0; j < allData.brands[i].KPI.length; j++) {
							if (allData.brands[i].brand == brand) {
								brandExists = true;
								if (allData.brands[i].KPI[j].Variable_Type == kpi) {
									allData.brands[i].KPI[j].Variable.push( dataKpi );
									kpiExists = true;
								}
							}
						};
					};
					if (!brandExists) {
						allData.brands.push({brand: brand, KPI: [], DRIVER: []});
					}
					if (!kpiExists) {
						allData.brands.forEach( function(brandTmp, i, arr) {
							if(brandTmp.brand == brand) {
								allData.brands[i].KPI.push( {Variable_Type: kpi, Variable: [dataKpi]});
							}
						});
					}
					brandExists = false;
					kpiExists = false;
				}
			});
			
			data.DRIVER.forEach( function(dataKpi) {
				if(dataKpi.Brand == brand && dataKpi.Variable_Type == kpi) {
					// Find and update
					for (i = 0; i < allData.brands.length; i++) {
						for (j = 0; j < allData.brands[i].DRIVER.length; j++) {
							if (allData.brands[i].brand == brand) {
								brandExists = true;
								if (allData.brands[i].DRIVER[j].Variable_Type == kpi) {
									allData.brands[i].DRIVER[j].Variable.push( dataKpi );
									kpiExists = true;
								}
							}
						};
					};
					if (!brandExists) {
						//allData.brands.push({brand: brand, KPI: [], DRIVER: []});
					}
					if (!kpiExists) {
						allData.brands.forEach( function(brandTmp, i, arr) {
							if(brandTmp.brand == brand) {
								allData.brands[i].DRIVER.push( {Variable_Type: kpi, Variable: [dataKpi]});
							}
						});
					}
					brandExists = false;
					kpiExists = false;
				}
			});
		});
	});
	
	
	console.log(allData);
	// Add time to allData
	allData.time = data.time;

	// Fill up KPI select box
	var tmpStr = "";
	allData.brands.forEach( function (brand) {
		//tmpStr += '<optgroup class="optGroupBrand" label="'+brand.brand+' :">';
		brand.KPI.forEach( function(kpi) {
			tmpStr += '<optgroup class="optGroupVarType" label="' + brand.brand + ' - ' +kpi.Variable_Type + ' :">';		
			kpi.Variable.forEach( function(varName) {
				tmpStr += '<option class="optGroupVariable" >'+ varName.VarName +'</option>';
			});
		});
		tmpStr += '</optgroup>';
		//tmpStr += '</optgroup>';
	});
	document.getElementById('kpiSelectInput').innerHTML = tmpStr;

	// Fill up DRIVER select box
	var tmpStr = "";
	allData.brands.forEach( function (brand) {
		//tmpStr += '<optgroup class="optGroupBrand" label="'+brand.brand+' :">';
		brand.DRIVER.forEach( function(driver) {
			tmpStr += '<optgroup class="optGroupVarType" label="' + brand.brand + ' - ' + driver.Variable_Type + ' :">';		
			driver.Variable.forEach( function(varName) {
				tmpStr += '<option class="optGroupVariable" >'+ varName.VarName +'</option>';
			});
		});
		tmpStr += '</optgroup>';
		//tmpStr += '</optgroup>';
	});
	document.getElementById('driverSelectInput').innerHTML = tmpStr;
}

// Update button pressed
function updateChart() {

	var selectedKpi = [];
	var selectedDriver = [];
	selectedKpi = getSelectedOptions('kpiSelectInput');
	selectedDriver = getSelectedOptions('driverSelectInput');
	var time = allData.time;
	$('#chartsOuterDiv').empty();
	allData.brands.forEach( function(brand) {
		brand.KPI.forEach( function(kpi) {
			kpi.Variable.forEach( function(Variable) {
				
				if ( selectedKpi.some( function(el) { return el == Variable.VarName; }) ) {
					// Found KPI
					//console.log( Variable);
					
					// Find Driver
					allData.brands.forEach( function(dBrand) {
						dBrand.DRIVER.forEach( function(driver) {
							driver.Variable.forEach( function(dVariable) {
								
								if ( selectedDriver.some( function(el) { return el == dVariable.VarName; }) ) {
									//Found Driver
									//console.log(dVariable);
									//Now Create Chart (Variable, dVariable)
									var tmpDiv = document.createElement('h3');
									tmpDiv.className = "queryChartHeader"
									tmpDiv.innerHTML = Variable.Brand + " &raquo; " + Variable.Variable_Type;
									document.getElementById('chartsOuterDiv').appendChild( tmpDiv );
									
									addCharts(Variable, dVariable, time);
								}
								
							});
						});
					});
					
				}		
			
			});
		});
	});	
}

/////////////////// INIT
$( document ).ready(function() {

	console.log("edaId " + edaId);
	console.log("projectId " + projectId);

	$.get( "viz/Compare/Compare.php", { edaId: edaId, projectId: projectId }, compareDataLoaded, "json" ).fail( function(err) {
		console.log("Compare DRIVER Charts ERROR!");
		console.log(err);
	});

});

/** Helper Funtions **/
function getAllUniqueBrands(data) {	
	
	var brands = [];
	data.KPI.forEach( function(el) {
		brands.push( el.Brand );
	});
	data.DRIVER.forEach( function(el) {
		brands.push( el.Brand );
	});
	data.OTHERS.forEach( function(el) {
		brands.push( el.Brand );
	});
	// Remove duplicates
	brands = brands.filter(function (v, i, a) { return a.indexOf(v) == i });
	return brands;
}

function getAllUniqueKPIs(data) {	
	
	var brands = [];
	data.KPI.forEach( function(el) {
		brands.push( el.Variable_Type );
	});
	data.DRIVER.forEach( function(el) {
		brands.push( el.Variable_Type );
	});
	data.OTHERS.forEach( function(el) {
		brands.push( el.Variable_Type );
	});
	// Remove duplicates
	brands = brands.filter(function (v, i, a) { return a.indexOf(v) == i });
	return brands;
}

// returns selected options in select multiple
function getSelectedOptions(selectId) {

	var sel = document.getElementById(selectId);
    var opts = [], opt;
    // loop through options in select list
    for (var i=0, len=sel.options.length; i<len; i++) {
		opt = sel.options[i];
        // check if selected
        if ( opt.selected ) {
			opts.push(opt.value);
		}
    }
	console.log(opts);
    // return array containing references to selected option elements
    return opts;
}
// Add charts
function addCharts(kpi, driver, time) {
	
	// Create Container for two charts
	var tmpDiv = document.createElement('div');
	tmpDiv.className = 'queryChartsRow';
	// create container for left bivariate chart
	var bivariate = document.createElement('div');
	bivariate.id = kpi.VarName.replace( /[^\w\d]/g, "_") + "_" + driver.VarName.replace( /[^\w\d]/g, "_") + "_BIVARIATE";
	bivariate.className = "queryBiVariateChart";
	// create container for right meandiff chart
	var meandiff = document.createElement('div');
	meandiff.id = kpi.VarName.replace( /[^\w\d]/g, "_") + "_" + driver.VarName.replace( /[^\w\d]/g, "_") + "_MEANDIFF";
	meandiff.className = "queryMeanDiffChart";
	
	// Add biVariate chart div to 2 chart container
	tmpDiv.appendChild( bivariate );
	tmpDiv.appendChild( meandiff );
	
	//tmpDiv.innerHTML = kpi.VarName + " :: " + driver.VarName;
	// Attach the div
	document.getElementById('chartsOuterDiv').appendChild( tmpDiv );

	// Create the chart in the new created div
	addBiVariateChart(kpi, driver, time, bivariate.id);
	var tmpKpi = convertToMeanDiff( $.extend(true, {}, kpi) );
	var tmpDriver = convertToMeanDiff( $.extend(true, {}, driver) );
	addMeanDiffChart(tmpKpi, tmpDriver, time, meandiff.id);
	
}
// Crete the actual bivariate chart
function addBiVariateChart(dep, indep, time, divId) {

	$( '#'+divId ).highcharts({
        chart: {
            zoomType: 'x'
        },
        title: {
            text: dep.VarName + " vs. " + indep.VarName,
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
                text: dep.VarName,
                style: {
                    //color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: indep.VarName,
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
            name: dep.VarName,
            type: 'line',
            data: dep.data,
			color: dep.color,
			zIndex: 1
        }, {
            name: indep.VarName,
            type: 'column',
			//color:'red',
			color: indep.color,
            data: indep.data,
            yAxis: 1,
        }],
		credits: false
    });


};

// Crete the actual Mean Diff chart
function addMeanDiffChart(dep, indep, time, divId) {

	// Create the data table.
	var i = 0;
	var dataArray = [];
	for(i=0; i < time.data.length; i++) {
		//dataArray.push( [time.data[i], dep.data[i], indep.data[i]] );
		dataArray.push( [dep.data[i], indep.data[i]] );
	}

    $( '#'+ divId ).highcharts({
        chart: {
            type: 'scatter',
            zoomType: 'xy'
        },
        title: {
            text: dep.VarName + " vs. " + indep.VarName,
			align: 'left',
			x: 70
        },
        xAxis: {
            title: {
                enabled: true,
                text: dep.VarName
            },
            startOnTick: true,
            endOnTick: true,
			plotLines : [{
				value : 0,
				color : 'lightgrey',
				dashStyle : 'dash',
				width : 1,
			}]
        },
        yAxis: {
            title: {
                text: indep.VarName
            },
			gridLineColor: 'transparent',
			plotLines : [{
				value : 0,
				color : 'lightgrey',
				dashStyle : 'dash',
				width : 1,
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
			headerFormat: '<b>{series.VarName}</b><br>',
			enabled: false
		},
		legend: {
            enabled: false
        },
        series: [{
			name: indep.VarName,
			color: indep.color,
            data: dataArray,
			regression: true ,
			regressionSettings: {
				showInLegend: false,
				type: 'linear',
				color: indep.color
			}
        }],
		credits: false
    });

};

// Convert series to mean - diff
function convertToMeanDiff( variable ) {

	var elmt = variable.data;
	var sum = 0;
	for( var i = 0; i < elmt.length; i++ ){
		sum += parseFloat( elmt[i] );
	}
	var avg = sum/elmt.length;
	
	for( var i = 0; i < elmt.length; i++ ){
		variable.data[i] = variable.data[i] - avg;
	}
	return variable;
}