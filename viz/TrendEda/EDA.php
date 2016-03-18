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

	$q = "SELECT * FROM `" . DATABASE . "." . $tableName ."`";
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
echo JSON_encode( getData($_GET['edaId'], $_GET['projectId']) );

//include 'EDA_Main.php';

?>