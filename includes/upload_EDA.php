<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
$_SESSION['tablecheck']="";
$_SESSION['tablename']="";


if (login_check($mysqli) == true) :
		$fname= $_POST["dataset"];
		 //echo $fname;
		if ($fname==null || $fname=="")
		{
		echo "<div align=center> Please mention a Dataset Name";
		?>
			<br></br>
			<a href="javascript:history.go(-1)">Go Back</a> </html>
		<?php
		return false;
		}
		$allowedExts = array("csv");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);

		if (in_array($extension, $allowedExts))
		{
		  if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		  } 
		  else {
			$filename  = basename($_FILES['file']['name']);
			$extension = pathinfo($filename, PATHINFO_EXTENSION);
			$new       = $fname.'.'.$extension;
			move_uploaded_file($_FILES['file']['tmp_name'], "../upload/{$new}");
			$filename="../upload/" . $new;
			$file = fopen($filename,"r");
			fclose($file);

		/********************************************************************************/
			// Parameters: filename.csv table_name
			$table = pathinfo($filename);
			$table = $table['filename'];
			$Projectid=$_SESSION['projectid'];
			$dataset=$table;
			$table=$table."_".$Projectid;
			

			/********************************************************************************/
			// Get the first row to create the column headings

			$fp = fopen($filename, 'r');
			$ccount = 0;
			//Mention all the characters that needs to be replaced, here
			$patterns=array('/"/','/\'/');
			while(! feof($fp))
			  {
				$frow = fgetcsv($fp);
				$arr[] = $frow;
				if($ccount>=4) :
				{
					foreach($frow as $column) {
						if(isset($columns)) $columns .= ',' ;					
						else $columns="";
						$column = preg_replace($patterns, '', trim($column));
						$columns .= "`". "$column"."`". " varchar(50)";
					}
					//var_dump($_SESSION);
					//var_dump(transpose($arr));
					break;
				}
				endif;
				$ccount++;
			  }
			fclose($fp);
			
			
			$create = "create table `$table` ($columns);";
			//echo $create;				
			$mysqli->query($create);
			
			if($mysqli->error) :
			{
				var_dump($mysqli->error );
				//$_SESSION[tablecheck]="DataSet name already exits!";
				$_SESSION[tablecheck]=$mysqli->error;
				$_SESSION[tablename]=$_POST["dataset"];
				header('Location: ../create_eda.php');
				return;
			}
			endif;
			
			/********************************************************************************/
			// Import the data into the newly created table.
			//var_dump($_FILES);
			$file = $_SERVER['CONTEXT_DOCUMENT_ROOT'].'webeda/upload/' . $new;
			$q_loaddata = "load data infile '$file' into table `$table` fields terminated by ',' ignore 5 lines";
			echo $q_loaddata;
			$mysqli->query($q_loaddata);
			echo $file;
			
			//var_dump( $mysqli->error );

			$q_insert_eda= "insert into eda_dataset(datasetname,tablename, projects_id, created_date,modified_date) values ('$dataset','$table',$Projectid,now(),now())";
			$mysqli->query($q_insert_eda);
			
			//below this write an insert statement 
			$ccount=0;
			$get_edaid_query = "select id from eda_dataset where projects_id=$Projectid and datasetname = '$dataset'";
			$edaid_arr = $mysqli->query($get_edaid_query);
			foreach($edaid_arr as $edaid_temp)
			{
				$edaid = stripslashes($edaid_temp['id']);
			}
			
			
			foreach (transpose($arr) as $value) {
				if($ccount<>0) :
				{
					$cleaned_col_name = preg_replace($patterns, '', trim($value[4]));
					$insert_query = "insert into eda_column_mapping (projectid,edaid,`Column Name`, Brand, Ownership,Variable,`Variable_Type`,created_date,modified_date) values ($_SESSION[projectid],$edaid,'$cleaned_col_name','$value[0]','$value[1]','$value[2]','$value[3]',now(),now())";
					//echo $insert_query;
					$mysqli->query($insert_query);
					
				}
				endif;
				$ccount++;
			}
			header('Location: ../eda.php');
			}
		}
		else {
		  echo "<br><br><br> Invalid file : Please upload a csv file";
		  ?>
			<br></br>
			<a href="javascript:history.go(-1)">Go Back</a> </html>
		<?php
		return false;
		}
else : 
?>
		<html>
					<p>
						<span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
					</p>
		</html>
 <?php endif; 
?>