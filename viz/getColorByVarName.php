<?php

//include_once '../includes/db_connect.php';

function getColorByVarName($varName = 'Airtel  nGRPs', $projectId = 13) {
	
	$q = "SELECT * FROM set_color_eda_model WHERE projectid = $projectId AND name = '$varName'";
	//echo $q;
	global $mysqli;
	
	$result = $mysqli->query($q);
	$color = '';
	foreach ($result as $row)
	{
		//var_dump($row);
		$color = $row['color'];
	}
	//echo($color);
	if(!$color) $color = null;
	return  $color;
}