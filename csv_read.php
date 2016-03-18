<?php
include_once 'includes/db_connect.php';
include_once 'includes/psl-config.php';


$filename="upload/SampleData.csv";
$file = fopen($filename,"r");
print_r(fgetcsv($file));


while(! feof($file))
  {
  var_dump(fgetcsv($file));
  }
fclose($file);

echo "End of read";

/********************************************************************************/
// Parameters: filename.csv table_name


    $table = pathinfo($filename);
    $table = $table['filename'];
	echo $table;
//}

/********************************************************************************/
// Get the first row to create the column headings

$fp = fopen($filename, 'r');
$frow = fgetcsv($fp);

$ccount = 0;
foreach($frow as $column) {
    $ccount++;
    if(isset($columns)) $columns .= ', ';
    else $columns="";
    $columns .= "`". "$column"."`". " varchar(50)";
}

$create = "create table if not exists $table ($columns);";
echo $create;
$mysqli->query($create);

/********************************************************************************/
// Import the data into the newly created table.

var_dump($_SERVER);
$file = $_SERVER['CONTEXT_DOCUMENT_ROOT'].'webeda/'.$filename;
$q = "load data infile '$file' into table $table fields terminated by ',' ignore 1 lines";
echo "<br><br>$q";
$mysqli->query($q);
?>