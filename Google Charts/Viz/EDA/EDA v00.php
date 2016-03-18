<?php

include_once '../includes/db_connect.php';

/*
*
*/
function getData($tableName = "sampledata_copy")
{
	global $mysqli;
	$q = "SELECT * FROM " . DATABASE . "." . $tableName;
	//echo $q . "<br>";
	$res = $mysqli->query($q);
	
	$names = getVariableNames($res); 
	
	// Save variable names
	$data[$tableName]['time']['name'] = $names['time'];
	$data[$tableName]['time']['data'] = [];
	$data[$tableName]['dependent']['name'] = $names['dependent'];
	$data[$tableName]['dependent']['data'] = [];
	for( $i = 0; $i < count($names['independent']); $i++ )
	{
		$data[$tableName]['independent'][$i]['name'] = $names['independent'][$i];
		$data[$tableName]['independent'][$i]['data'] = [];
	}
	
	// Save variables data
	while( $row = mysqli_fetch_assoc($res) )
	{
		$data[$tableName]['time']['data'][] = $row[ $names['time'] ];
		$data[$tableName]['dependent']['data'][] = floatval( $row[ $names['dependent'] ] );
		for( $i=0; $i < count($names['independent']); $i++ )
		{	
			//echo $names['independent'][$i] . " - " . $row[ $names['independent'][$i] ] . "<br>";
			$data[$tableName]['independent'][$i]['data'][] = floatval( $row[ $names['independent'][$i] ] );
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
echo JSON_encode( getData() );

//include 'EDA_Main.php';

?>