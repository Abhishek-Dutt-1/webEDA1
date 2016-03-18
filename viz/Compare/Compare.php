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
	
	foreach ($result as $row) {
		$tableName = $row['tablename'];
	}

	$q = "SELECT * FROM " .  DATABASE . ".`" . $tableName . "`";
	$res = $mysqli->query($q);

	$names = getVariableNames($res, $edaId, $projectId);
	$data = []; $typeTmp = [];
	//var_dump($names);

	// Save Variable Type info
	// // Time is always first
	$data['time'] = $names['time'];
	$data['time']['data']  = [];
	$data['time']['color'] = getColorByVarName($names['time']['VarName'], $projectId);

	// Save variables data
	$data['KPI'] = [];
	$data['DRIVER'] = [];
	$data['OTHERS'] = [];
	
	for ($i=0; $i < count($names['KPI']); $i++) {
		$data['KPI'][$i] = $names['KPI'][$i];
		$data['KPI'][$i]['color'] = getColorByVarName($names['KPI'][$i]['VarName'], $projectId);
	}
	for ($i=0; $i < count($names['DRIVER']); $i++) {
		$data['DRIVER'][$i] = $names['DRIVER'][$i];
		$data['DRIVER'][$i]['color'] = getColorByVarName($names['DRIVER'][$i]['VarName'], $projectId);
	}
	for ($i=0; $i < count($names['OTHERS']); $i++) {
		$data['OTHERS'][$i] = $names['OTHERS'][$i];
		$data['OTHERS'][$i]['color'] = getColorByVarName($names['OTHERS'][$i]['VarName'], $projectId);
	}

	while ($row = mysqli_fetch_assoc($res)) {
		$data['time']['data'][] = $row[ $names['time']['VarName'] ];									// First column is always Time
		for ($i=0; $i < count($names['KPI']); $i++) {
			$data['KPI'][$i]['data'][] = floatval( $row[ $names['KPI'][$i]['VarName'] ] );			// In same order as above for loop
		}
		for ($i=0; $i < count($names['DRIVER']); $i++) {
			$data['DRIVER'][$i]['data'][] = floatval( $row[ $names['DRIVER'][$i]['VarName'] ] );			// In same order as above for loop
		}
		for ($i=0; $i < count($names['OTHERS']); $i++) {
			$data['OTHERS'][$i]['data'][] = floatval( $row[ $names['DRIVER'][$i]['VarName'] ] );			// In same order as above for loop
		}
	}
	//var_dump($data);
	return $data;
}

/* 
 * Simply returns column names w/o any fancy rearrangement
 */
function getVariableNames($res, $edaId, $projectId)
{
	$variables = [];
	$tmp = $res->fetch_fields();
	$variables['time']['VarName'] = array_shift($tmp)->name;		// First field is always Time
	$variables['KPI'] = [];
	$variables['DRIVER'] = [];
	$variables['OTHERS'] = [];
	foreach ($tmp as $var) {
		$typeTmp = getVariableTypeData($var->name, $edaId, $projectId);
		if (strtoupper($typeTmp['Variable']) == 'KPI') {
			$variables['KPI'][] = $typeTmp;
		}
		if (strtoupper($typeTmp['Variable']) == 'DRIVER') {
			$variables['DRIVER'][] = $typeTmp;
		}
		// There can be others, mistypes etc. 
		if (!((strtoupper($typeTmp['Variable']) == 'KPI')||(strtoupper($typeTmp['Variable']) == 'DRIVER'))) {
			$variables['OTHERS'][] = $typeTmp;
		}
	}
	return $variables;
}

/*
 * Returns variable types info by name
 */
function getVariableTypeData($name, $edaId, $projectId) {
	$q = "SELECT `Column Name` as VarName, Brand, Ownership, Variable, `Variable_Type` FROM `eda_column_mapping` WHERE `Column Name` = '$name' AND projectid = '$projectId' AND edaid = '$edaId'";
	global $mysqli;
	$typeData = $mysqli->query($q);
	return mysqli_fetch_assoc($typeData);
}

echo JSON_encode( getData($_GET['edaId'], $_GET['projectId']) );

?>