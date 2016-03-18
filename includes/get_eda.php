<?php
include_once 'db_connect.php';
include_once 'functions.php';

$q = intval($_GET['q']);


$q_GetEDA="SELECT id,datasetname fROM eda_dataset WHERE projects_id = $q ";				
$edaresult = $mysqli->query($q_GetEDA);
if ($edaresult->num_rows==0)
{
  return;
}
$selectbox = '<div class="select-wrapper">';
$selectbox.='<select id = "name" name="name"  onchange="showgrid(this.value)">';
$selectbox.= '<option value="">- EDA Dataset Name -</option>';
foreach ( $edaresult as $row) {
 $selectbox.='<option value="' . $row['id'] . '">' . $row['datasetname'] . '</option>';
}
 $selectbox.='</select>';
 $selectbox.='</div>';

 echo $selectbox;

?>