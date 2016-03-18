<?php
/* 
$edaId, $modelId
//get $modelTable and $modelName from model_mapping
SELECT * FROM 'model_mapping' WHERE id = $modelId

SELECT * FROM $modelTable WHERE 'Model No.' = $modelName

create modelObj

getData($edaId)

create retObj {modelObj, $edaId}

calcContribSeries(retObj)
calcAvContrib(retObj)
*/
/*
$_GET['edaId']
$_GET['modelId']
*/

include_once '../../includes/db_connect.php';
include_once '../getColorByVarName.php';

function getFinalData($edaId, $modelId, $projectId) {
	 
	$finalData['modelData'] = getModelData($modelId);
	$finalData['tableData'] = getData($edaId, $projectId);
	
	foreach( $finalData['modelData']['independent'] as $indep)
	{
		$indepNames[] = $indep['name'];
	}
	// delete data that is not in models
	foreach( $finalData['tableData']['independent'] as $key => $indep)
	{
		if( !in_array($indep['name'], $indepNames) ) {
			unset( $finalData['tableData']['independent'][$key] );
		}
	}
	// Check if some variable is missing
	if( count( $finalData['tableData']['independent'] ) != count($indepNames) )
	{
		return false;
	}
	else {
		// merge tableData and modelData
		$finalData['modelData']['dependent'] = $finalData['tableData']['dependent'];
		$finalData['modelData']['time'] = $finalData['tableData']['time'];
		$finalData['modelData']['info'] = $finalData['tableData']['info'];
		
		foreach( $finalData['modelData']['independent'] as $k => $in )
		{
			foreach( $finalData['tableData']['independent'] as $key => $indep)
			{
				if($in['name'] == $indep['name'])
				{
					$finalData['modelData']['independent'][$k]['data'] = $indep['data'];
					$finalData['modelData']['independent'][$k]['color'] = $indep['color'];
				}
			}
		}
		unset( $finalData['tableData'] );
		return $finalData;
	}
}

// Fetch data from the Models tables
function getModelData($modelId)
{
	if(!$modelId) return;
	global $mysqli;

	$r = $mysqli->query( "SELECT * FROM model_mapping WHERE id = $modelId" );
	foreach( $r as $row)
	{
		//var_dump($row);
		$modelTable = $row['model_table'];
		$modelName = $row['model_name'];
	}
	$q = "SELECT * FROM `$modelTable` WHERE `Model No.` = '$modelName'" ;
	$r = $mysqli->query( $q );
	foreach($r as $row)
	{
		//var_dump($row);
		$model = $row;
	}
	//var_dump($model);

	// Count the number of variables in the model
	$i=1;
	while(true)
	{
		//echo isset( $model['name_ind'.$i] );
		if( !isset( $model['name_ind'.$i] ) ) break;
		if( trim( $model['name_ind'.$i] ) === '' ) break;		
		$i++;
	}
	
	$numVars = $i-1;
	$varArray = [];
	for($i = 1; $i <= $numVars; $i++)
	{
		$tmp['name'] = explode("_", trim($model['name_ind'.$i]) )[0];
		$tmp['co'] = (float)( trim(explode("_", trim($model['name_ind'.$i]) )[1]) );
		$tmp['power'] = (float)( trim(explode("_", trim($model['name_ind'.$i]) )[2]) );
		$tmp['coef'] = (float)( trim($model['coef_var'.$i]) );
		$tmp['tstat'] = (float)( trim($model['t_ind'.$i]) );
		$varArray[] = $tmp;
	}
	//var_dump( $model );
	unset($tmp);
	$tmp['name'] = "Intercept";
	$tmp['coef'] = (float)( trim($model['coef_int']) );
	$tmp['tstat'] = (float)( trim($model['t_int']) );

	$modelObj["intercept"] = $tmp;
	$modelObj["modelName"] = trim( $model["Model No."] );
	$modelObj["rsquare"] = (float)( trim( $model["rsquare"] ) );
	$modelObj["adjr2"] = (float)( trim( $model["adjr2"] ) );
	$modelObj["fstat"] = (float)( trim( $model["fstat"] ) );
	$modelObj["dw"] = (float)( trim( $model["dw"] ) );
	$modelObj["numVars"] = $numVars;
	$modelObj["independent"] = $varArray;
	//var_dump( $modelObj );
	return $modelObj;
}



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
////////////////////////
function calcContributionSeries($data)
{
	if(!$data) return;
	foreach( $data['modelData']['independent'] as $key => $indep)
	{
		$data['modelData']['independent'][$key]['coSeries'] = calcCOSeries($indep['data'], $indep['co']);
		$data['modelData']['independent'][$key]['powerSeries'] = calcPowerSeries($data['modelData']['independent'][$key]['coSeries'], $indep['power']);
		$data['modelData']['independent'][$key]['contribSeries'] = calcContribSeries($data['modelData']['independent'][$key]['powerSeries'], $indep['coef']);
	}
	$data['modelData']['intercept']['contribSeries'] = array_fill(0, count($indep['data']), $data['modelData']['intercept']['coef']);
	return $data;
}

/*
 * AdStock functions
*/
function calcCOSeries($data, $co)
{
	$retArray = [];
	$retArray[0] = $data[0];
	for($i = 1, $j = count($data); $i < $j; $i++)
	{
		$retArray[$i] = $data[$i] + ( $retArray[$i-1] * $co);
	}
	return $retArray;
}
function calcPowerSeries($coSeries, $power) {
	return array_map( function($n) use ($power) { return pow($n, $power); }, $coSeries);
}
function calcContribSeries($powerSeries, $coef) {
	return array_map( function($n) use ($coef) { return $n*$coef; }, $powerSeries);
}

/*
 * Calculate limits for yAxis of Contribution Series Chart to positive and negative variables do not overlap
 */
function calcLimitsForAxes($data)
{

	$pos = array_fill(0, count($data['modelData']['dependent']['data']), 0);
	$neg = array_fill(0, count($data['modelData']['dependent']['data']), 0);

	foreach($data['modelData']['independent'] as $indep)
	{
		foreach($indep['contribSeries'] as $in => $dataPoint)
		{
			if($indep['coef'] >= 0)
			{
				$pos[$in] += $dataPoint;
			}
			else
			{
				$neg[$in] += $dataPoint;
			}
		}
	}
	// Add intercept to positive contribSeries
	$pos = array_map( function(){ return array_sum(func_get_args()); }, $pos, $data['modelData']['intercept']['contribSeries'] );
	// get the max of either series
	$yAxisLimit = ( max($pos) > abs( min($neg) ) ) ? max($pos) : abs(min($neg));
	$yAxisLimit = ( max($data['modelData']['dependent']['data']) > $yAxisLimit )  ? max($data['modelData']['dependent']['data']) : $yAxisLimit;
	$data['modelData']['yAxisMax'] = $yAxisLimit;
	return $data;
}

/*
 * Calculate average contributions
 * Av Contribution Variable(i)= Sum(Variable(i)(contribSeries)) / Sum(AllContribSeries)
 */
function calcAverageContribution( $data )
{
	$contribDen = array_sum( $data['modelData']['intercept']['contribSeries'] );
	foreach($data['modelData']['independent'] as $indep)
	{
		$contribDen += 	array_sum( $indep['contribSeries'] );
	}
	foreach($data['modelData']['independent'] as $key => $indep)
	{
		$data['modelData']['independent'][$key]['averageContribution'] = array_sum( $indep['contribSeries'] ) / $contribDen;
	}
	$data['modelData']['intercept']['averageContribution'] = array_sum( $data['modelData']['intercept']['contribSeries'] ) / $contribDen;
	//var_dump($data['modelData']['independent']);
	return $data;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function calcPredictedSeries($data) {

	$yPred = array();
	for ($i = 0; $i < count($data['modelData']['dependent']['data']); $i++) {
		$yPred[] = $data['modelData']['intercept']['contribSeries'][$i];
		foreach ($data['modelData']['independent'] as $indep) {
			$yPred[count($yPred) - 1] += $indep['contribSeries'][$i];
		}
	}
	$data['modelData']['predicted']['data'] = $yPred;
	$data['modelData']['predicted']['name'] = "Pred(" .$data['modelData']['dependent']['name'] .")";
	return $data;
}

if($_GET['dataType'] == 'contribSeries') {
	$data = getFinalData( $_GET['edaId'], $_GET['modelId'], $_GET['projectId'] );
	$data = calcContributionSeries($data);
	$data = calcLimitsForAxes($data);
	$data = calcAverageContribution($data);
	echo JSON_encode( $data );
}
if($_GET['dataType'] == 'saturationCurve') {
	$data = getFinalData( $_GET['edaId'], $_GET['modelId'], $_GET['projectId'] );
	$data = calcContributionSeries($data);
	$data = calcLimitsForAxes($data);
	$data = calcAverageContribution($data);
	echo JSON_encode( $data );
}
if($_GET['dataType'] == 'simlutionData') {
	$data = getFinalData( $_GET['edaId'], $_GET['modelId'], $_GET['projectId'] );
	$data = calcContributionSeries($data);
	$data = calcLimitsForAxes($data);
	$data = calcAverageContribution($data);
	echo JSON_encode( $data );
}
if($_GET['dataType'] == 'AvP') {
	$data = getFinalData( $_GET['edaId'], $_GET['modelId'], $_GET['projectId'] );
	$data = calcContributionSeries($data);
	$data = calcLimitsForAxes($data);
	$data = calcAverageContribution($data);

	$data = calcPredictedSeries($data);

	echo JSON_encode( $data );
}