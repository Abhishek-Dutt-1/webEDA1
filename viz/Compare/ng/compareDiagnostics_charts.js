var allData = {};
var selectionData = {};
var MAX_TABLE_COLUMNS = 3;
/////////////////////////////////////////////
function compareDiagnosticsDataLoaded(data) {

	console.log(data);

	var brands = [];
	var kpis = [];
    
	brands = getAllUniqueBrands(data);
	kpis = getAllUniqueKPIs(data);

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

	// Add time to allData
	allData.time = data.time;
	// First run initialization
	// Initialize with all data
	selectionData = JSON.parse(JSON.stringify(allData));
    console.log(selectionData);
	// get list of variable types to show in first column
	selectionData.selectedVarTypes = getAllSelectionVariableTypes(selectionData);
	// fill Variable array by blanks so that template can show empty divs
	selectionData = augmentKPIVariableArray(selectionData);
    // fill KPIs with missing values
    //selectionData = augmentKPIArray(selectionData); 
	updateSelectionTable();
	console.log(selectionData);
}

// Update Selection Table
function updateSelectionTable() {

	$('#selectionTableContainer').empty();
	
	if( selectionData.brands.length < MAX_TABLE_COLUMNS ) {
		selectionData.brands.push({ brand: false });
	}
		
	// compile the template
	var buildingTemplate = dust.compile($("#selectionTableTemplate").html(), "tableTemplate");
	// load the compiled template into the dust template cache
	dust.loadSource(buildingTemplate);
	// create a function that takes the data object
	// in this case it's a 'building' object
	var template = function(building) {	
		var result;	
		dust.render("tableTemplate", building, function(err, res) {
			result = res;
		});	
		return result;
	};

	// append the template to it's host container
	//$("#someID").html(template(building));
	$("#selectionTableContainer").html(template(selectionData));
		
	// Create individual charts
	selectionData.brands.forEach( function(brand, ind) {
		if(brand.kpis) {
			brand.kpis.forEach( function(kpi, i) {
				kpi.info.forEach( function(data, index) {
					drawSparkLineChart(data);
				});
			});
		}
	});
}

/////////////////// INIT
$( document ).ready(function() {

    console.log(edaId);
    console.log(projectId);

	$.get( "viz/Compare/Compare.php", { edaId: edaId, projectId: projectId }, compareDiagnosticsDataLoaded, "json" ).fail( function(err) {
		console.log("ERROR");
		console.log(err);
	});

});

/** * Helper Funtions * **/
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

// Get all uniques variable types
function getAllSelectionVariableTypes(data) {
	var varTypes = [];
	var varCounts = [];
	var len = 0;
	var i = 0;
	var tmpMaxCount = 0;
	var tmpKpi = [];

	data.brands.forEach( function(brand) {
		brand.KPI.forEach( function(kpi) {
			varTypes.push( kpi.Variable_Type );
		});
	});
	// Remove duplicates
	varTypes = varTypes.filter(function (v, i, a) { return a.indexOf(v) == i; });
    varTypes.forEach( function(tmp) {
        varCounts.push( { varType: tmp, maxCount: 0} );
    });

    data.brands.forEach( function(brand) {
        brand.KPI.forEach( function(kpi) {
            if(kpi) {
                for (i=0; i<varCounts.length; i++) {
                    if(varCounts[i].varType == kpi.Variable_Type) {
                        varCounts[i].maxCount = varCounts[i].maxCount > kpi.Variable.length ? varCounts[i].maxCount : kpi.Variable.length;
                    }
                }
            }
        });
    });

    /*
	// Get the max count by brand
	varTypes.forEach( function(el) {
	
		data.brands.forEach( function(brand) {
		
			brand.KPI.forEach( function(kpi) {
		
				tmpKpi = varCounts.filter( function(tmp) { return tmp.kpiType == el; });
				if (tmpKpi.length > 0) {
					tmpMaxCount = tmpKpi.maxCount > kpi.Variable.length ? tmpKpi.maxCount : kpi.Variable.length;
					for (i = 0; i < varCounts.length; i++) {
						if (varCounts[i].kpiType == kpi.Variable_Type) {
							varCounts[i].maxCount = tmpMaxCount;
						}
					}
				} else {
					varCounts.push( {kpiType: el, maxCount: 0} );
				}
			});
		});
	});
    */
	return varCounts;
}

// fill Variable array by blanks so that template can show empty divs
function augmentKPIVariableArray(selData) {

    selData.brands.forEach( function(brand) {
        brand.KPI.forEach( function(kpi) {

            while( kpi.Variable.length < getMaxCountByKPI(kpi.Variable_Type) ) {
                kpi.Variable.push( {Brand: false} );
            }
            //console.log(kpi.Variable_Type + " :: " + getMaxCountByKPI(kpi.Variable_Type) + " :: " +  kpi.Variable.length);
        });
    });

	return selData;
}
function getMaxCountByKPI(varType) {
    return selectionData.selectedVarTypes.filter( function(el) { return el.varType == varType; })[0].maxCount;
}
// // //
function augmentKPIArray(selData) {

    selData.brands.forEach( function(brand) {
        brand.KPI.forEach( function(kpi) {
             
        });
    });
    return selData;
}
