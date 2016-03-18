<?php
include_once 'db_connect.php';
include_once 'functions.php';

$q = intval($_GET['q']);


$q_GetUserRole="SELECT role  FROM members WHERE id = $q";				
$userrole = $mysqli->query($q_GetUserRole);

if ($userrole->num_rows==0)
{
  return;
}
//$selectbox = '<b>User Role</b>';
$selectbox = '<div class="row uniform half collapse-at-2">';

$selectbox .= '<div class="4u">';
									
foreach ( $userrole as $row) {
	if($row['role']==1) :
	{
		$selectbox .= '<input type="radio" id="1" value="1" name="role" checked>';
		$selectbox .= '<label for="1">Admin</label>';
		$selectbox .= '</div>';
		$selectbox .= '<div class="4u">';
		$selectbox .= '<input type="radio" id="2"   value="2"  name="role">';
		$selectbox .= '<label for="2">Client</label>';
	}
	else :
	{
		$selectbox .= '<input type="radio" id="1"  value="1" name="role" >';
		$selectbox .= '<label for="1">Admin</label>';
		$selectbox .= '</div>';
		$selectbox .= '<div class="4u">';
		$selectbox .= '<input type="radio" id="2"  value="2" name="role" checked>';
		$selectbox .= '<label for="2">Client</label>';	
	}
	endif;
}
$selectbox .= '</div>';
$selectbox.='</div>';
 
 

 echo $selectbox;

?>