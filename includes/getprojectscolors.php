<?php
include_once 'db_connect.php';
include_once 'functions.php';

$q = intval($_GET['q']);

$q_GetColors="SELECT map.name,map.color FROM  `set_color_eda_model` map where map.projectid = $q";				
$result = $mysqli->query($q_GetColors);
if ($result->num_rows==0)
{
  return;
}
 $projectscolor ='<hr><h3>Below is the list of mapped brand color</h3>';

$projectscolor .= '<div class="row uniform half">';
$projectscolor .= '<table>';
foreach ( $result as $row) {
	$projectscolor .= 	'<tr><div class="6u">';
	$projectscolor .= 	'	<td><input type="checkbox" id="' . $row['name'] . '" name="' . $row['name'] . '">';
	$projectscolor .= 	'	<label for="' . $row['name'] . '">' . $row['name'] . '</label></td>';
	$projectscolor .= 	'	<td><input type="color" name="' . $row['name'] . ' "color" id="color" value="' . $row['color'] . '" /></td>';
	
	$projectscolor .= 	'</div><tr>';
}

$projectscolor .= '<table>';
$projectscolor .= '</div>';

$projectscolor .= '<div class="row uniform">';
$projectscolor .= '<div class="12u">';
$projectscolor .= '		<ul class="actions">';
$projectscolor .= '			<li><input type="submit" value="Update" name = "Action" class="button special"/></li>';
$projectscolor .= '			<li><input type="submit" onclick="return confirm_delete();" value="Delete" name = "Action"/></li>';
$projectscolor .= '			<li><input type="reset" value="Reset" class="alt" /></li>';
$projectscolor .= '		</ul>';
$projectscolor .= '</div>';
$projectscolor .= '</div>';
//$projectscolor .= '</form>';

echo $projectscolor;


?>