<?php
include_once 'db_connect.php';
include_once 'functions.php';


$q = intval($_GET['q']);

$q_GetEdaMapping="SELECT 	`id`,`Column Name`, `Brand`, `Ownership`, `Variable`, `Variable Type` FROM `webeda`.`eda_column_mapping` map where map.edaid = $q";				
$result = $mysqli->query($q_GetEdaMapping);
if ($result->num_rows==0)
{
  return;
}
foreach ( $result as $row) {
	$results[] = $row;
}
echo json_encode($results);
// $pass='{ metadata: [
					// { name: "name", datatype: "string", editable: true },
					// { name: "firstname", datatype: "string", editable: true },
					// { name: "age", datatype: "integer", editable: true },
					// { name: "height", datatype: "double(m,2)", editable: true },
					// { name: "email", datatype: "email", editable: true },
					// { name: "freelance", datatype: "boolean", editable: true },
					// { name: "lastvisit", datatype: "date", editable: true }
				// ]}';

				
				
//echo $pass;


?>