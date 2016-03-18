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
	//var_dump( $names );
	
	while ($row = mysqli_fetch_assoc($res)) {
		$rows[] = $row;
	}

	$data = [];
	foreach($names as $key=>$val) {
		if($names[$val] == 'TIME') {
			$data[] = 'TIME';
		} else {
			if($val[$varName]['Ownership'] == 'Own' ) {
				if($val[$varName]['Variable'] == 'KPI' ) {
					$data['OWN']['BRANDS']['KPI'][]['brand'] = $val[$varName]['Brand'] ;
					//$data['OWN']['BRANDS']['KPI'][$names[$varName]['Variable_Type']]['Variable_Type'] = $names[$varName]['Variable_Type'];
				}
			}
		}
	}
	var_dump($data);
	exit;
	
	foreach ($rows as $val) {
		foreach($val as $varName => $val) {
			if($names[$varName] == 'TIME') {
				$data['TIME']['data'][] = $val;
				//echo "TIME <br>";
			} else {
				if($names[$varName]['Ownership'] == 'Own' ) {
					if($names[$varName]['Variable'] == 'KPI' ) {
						$data['OWN']['BRANDS']['KPI'][$names[$varName]['Variable_Type']]['Variable_Type'] = $names[$varName]['Variable_Type'];
						$data['OWN']['BRANDS']['KPI'][$names[$varName]['Variable_Type']]['data'][] = $val;
						$data['OWN']['BRANDS']['KPI'][$names[$varName]['Variable_Type']]['INFO'] = $names[$varName];
					}
				}
			}
			//var_dump( $varName . " - " . $val );
		}
	}
	//var_dump($data["OWN"]["BRANDS"]["KPI"]);
	return $data;
}

/* 
 * Simply returns column names w/o any fancy rearrangement
 */
function getVariableNames($res, $edaId, $projectId)
{
	$variables = [];
	$tmp = $res->fetch_fields();
	$variables[array_shift($tmp)->name] = 'TIME';		// First field is always Time
	foreach ($tmp as $var) {
		
		//var_dump( getVariableTypeData($var->name, $edaId, $projectId) );
		$variables[ $var->name ] = getVariableTypeData($var->name, $edaId, $projectId);
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