var allData = {};
var selectionData = {};
var MAX_TABLE_COLUMNS = 3;
/////////////////////////////////////////////
function compareDiagnosticsDataLoaded(data) {
	console.log(data);
}

/////////////////// INIT
$( document ).ready(function() {
	console.log("Eda ID: " + edaId);
	console.log("Project ID: " + projectId);
	$.get( "viz/Compare/CompareDiagnostics.php", { edaId: edaId, projectId: projectId }, compareDiagnosticsDataLoaded, "json" ).fail( function(err) {
		console.log("ERROR");
		console.log(err);
	});

});