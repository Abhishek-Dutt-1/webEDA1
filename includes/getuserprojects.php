<?php
include_once 'db_connect.php';
include_once 'functions.php';

$q = intval($_GET['q']);

$q_GetUsers="SELECT p.name FROM  mapping_members_projects map,projects p where map.projects_id = p.id and members_id=$q";				
$result = $mysqli->query($q_GetUsers);

if ($result->num_rows==0)
{
  return;
}
echo "<h3>This user has access to following projects</h3><table border='1'>
<div class='table-wrapper'>
<tr>
</tr>";

foreach ( $result as $row) {
  echo "<tr>";
  echo "<td>" . $row['name'] . "</td>"; 
  echo "</tr>";
}
echo "</table>";

?>