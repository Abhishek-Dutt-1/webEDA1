<?php

include_once '../../includes/db_connect.php';
include_once '../getColorByVarName.php';
/*
*
*/
function getData($edaId, $projectId)
{
	if(!$edaId) return;
	global $mysqli;
	
	$q = "SELECT * FROM eda_dataset WHERE id = $edaId";
	$result = $mysqli->query($q);
	foreach ( $result as $row)
	{
		//var_dump($row);
		$tableName = $row['tablename'];
	}

	$q = "SELECT * FROM " . DATABASE . "." . $tableName;
	$q = "SELECT * FROM " .  DATABASE . ".`" . $tableName . "`";
	//echo $q . "<br>";
	$res = $mysqli->query($q);
	$names = getVariableNames($res); 
	
	// Save table name == Dataset name
	$data['info']['name'] = $tableName;
	// Save variable names
	$data['time']['name'] = $names['time'];
	$data['time']['data'] = [];
	$data['dependent']['name'] = $names['dependent'];
	$data['dependent']['data'] = [];
	
	$data['dependent']['color'] = getColorByVarName($names['dependent'], $projectId);
	
	for( $i = 0; $i < count($names['independent']); $i++ )
	{
		$data['independent'][$i]['name'] = $names['independent'][$i];
		$data['independent'][$i]['data'] = [];
		$data['independent'][$i]['color'] = getColorByVarName($names['independent'][$i], $projectId);
	}
	
	// Save variables data
	while( $row = mysqli_fetch_assoc($res) )
	{
		$data['time']['data'][] = $row[ $names['time'] ];
		$data['dependent']['data'][] = floatval( $row[ $names['dependent'] ] );
		for( $i=0; $i < count($names['independent']); $i++ )
		{	
			//echo $names['independent'][$i] . " - " . $row[ $names['independent'][$i] ] . "<br>";
			$data['independent'][$i]['data'][] = floatval( $row[ $names['independent'][$i] ] );
		}
	}
	//var_dump($data);
	return $data;
	//return json_encode($data);
}

/* 
* Returns the field names in a table
* param: query object
*/
function getVariableNames($res)
{
	$variables = [];
	$tmp = $res->fetch_fields();
	$variables['time'] = array_shift($tmp)->name;
	$variables['dependent'] = array_shift($tmp)->name;
	foreach( $tmp as $var)
	{
		$variables['independent'][] = $var->name;
	}
	return $variables;
}

/*
 * Convert table data to Mean Diff chart data
 */
function calcMeanDiff( $data )
{
	$depAv = array_sum( $data['dependent']['data'] )/count( $data['dependent']['data'] );
	$data['dependent']['average'] = $depAv;
	$data['dependent']['count'] = count( $data['dependent']['data'] );
	$data['dependent']['sum'] = array_sum( $data['dependent']['data'] );
	
	// Subtract mean from dependent
	for($i = 0; $i < count( $data['dependent']['data'] ); $i++) {
		$data['dependent']['data'][$i] = $data['dependent']['data'][$i] - $depAv;
	}
	// Subtract mean from independents
	for($i = 0; $i < count( $data['independent'] ); $i++) {
	
		$indepAv = array_sum( $data['independent'][$i]['data'] )/count( $data['independent'][$i]['data'] );
		$data['independent'][$i]['average'] = $indepAv;
		$data['independent'][$i]['count'] = count( $data['independent'][$i]['data'] );
		$data['independent'][$i]['sum'] = array_sum( $data['independent'][$i]['data'] );
	
		for($j = 0; $j < count( $data['independent'][$i]['data'] ); $j++) {
			$data['independent'][$i]['data'][$j] = $data['independent'][$i]['data'][$j] - $indepAv;
		}
	}
	
	return $data;
}

/*
 * Convert original data from table into Diagnostic charts compatible data
 * Default number of Bins = 10
 */
function calcDiagnostics( $data, $numBins = 10 )
{
	$binData = [];
	/*	TODO
	if(is_numeric(UserForm8.numBins)) {
        if(UserForm8.numBins > 0) {
            $numBins = UserForm8.numBins
        }
    }
	*/

	// Create bins
	for($i = 0; $i < count( $data['independent'] ); $i++) {
	
		$max = max($data['independent'][$i]['data']);
		$min = min($data['independent'][$i]['data']);
		//$average = array_sum( $data['independent'][$i]['data'] )/count( $data['independent'][$i]['data'] );
		//$stddev = WorksheetFunction.StDev(xCol);
		$binSize = ($max - $min) / $numBins;
		
		$binData['independent'][$i]['name'] = $data['independent'][$i]['name'];
		$binData['independent'][$i]['color'] = $data['independent'][$i]['color'];
		$binData['independent'][$i]['max'] = $max;
		$binData['independent'][$i]['min'] = $min;
		$binData['independent'][$i]['binSize'] = $binSize;
		$binData['independent'][$i]['numBins'] = $numBins;
		
		// Lowest bin value is the minimum of the series
		$binData['independent'][$i]['bins'][0] = $min;
		$binData['independent'][$i]['binsTxt'][0] = (string)round($min);
		$binData['independent'][$i]['data'][0] = count( array_filter( $data['independent'][$i]['data'], function($val) use ($min) {
													return $val <= $min;
												}) );
		$binData['independent'][$i]['Bin-Freq'][0] = $min.' = '.$binData['independent'][$i]['data'][0];
		
		for($j = 1; $j <= $numBins-1; $j++)
		{
			// Rest all bin labels = last bin + binSize
			//$binData['independent'][$i]['bins'][$j] = $binData['independent'][$i]['bins'][$j-1] + $binSize;
			// Rest all bin labels = RoundUpToNearest50( last bin + binSize )
			$binData['independent'][$i]['bins'][$j] = ceil( ($binData['independent'][$i]['bins'][$j-1] + $binSize) / 50) * 50; 
			// Current bin range
			$low = $binData['independent'][$i]['bins'][$j-1];
			$high = $binData['independent'][$i]['bins'][$j];
			// Make bins text user friendly
			$binData['independent'][$i]['binsTxt'][$j] = '('.round($low).'-'.round($high).']';
			// Count frequencies side by side
			$binData['independent'][$i]['data'][$j] = count( array_filter( $data['independent'][$i]['data'], function($val) use ($low, $high) {
															return ( $val > $low && $val <= $high );
														}) );
			$binData['independent'][$i]['Bin-Freq'][$j] = '('.round($low).'-'.round($high).'] = '.$binData['independent'][$i]['data'][$j];
		}
		// Last bucket = More+
		$binData['independent'][$i]['bins'][$j] = "More";
		$binData['independent'][$i]['binsTxt'][$j] = "More";
		$binData['independent'][$i]['data'][$j] = count( array_filter( $data['independent'][$i]['data'], function($val) use ($high) {
														return $val > $high;
													}) );
		$binData['independent'][$i]['Bin-Freq'][$j] = 'More = '.$binData['independent'][$i]['data'][$j];
	}
	
	return $binData;
}


// Throw it out
echo JSON_encode( calcDiagnostics(getData($_GET['edaId'], $_GET['projectId'])) );

?>