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
		echo "<div align='center'> Please mention the Model Name";
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
		  } else {
			//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			//echo "Type: " . $_FILES["file"]["type"] . "<br>";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
			$filename  = basename($_FILES['file']['name']);
			$extension = pathinfo($filename, PATHINFO_EXTENSION);
			$new       = $fname.'.'.$extension;
			//if (file_exists("../upload/" . $new)) {
			//  echo "<br><br><br>".$new . " already exists. ";
			//} else {
			  
			  
			  move_uploaded_file($_FILES['file']['tmp_name'], "../upload/{$new}");
			  //echo "<br><br><br>Stored in: " . "upload/" . $new;
			  
				$filename="../upload/" . $new;
				$file = fopen($filename,"r");
				
				fclose($file);


				/********************************************************************************/
				// Parameters: filename.csv table_name

					$table = pathinfo($filename);
					$table = $table['filename'];
					$Projectid=$_SESSION['projectid'];
					$Edaid=$_SESSION['edaId'];
					$dataset=$table;
					$table=$table."_".$Projectid."_".$Edaid;
				//}

				/********************************************************************************/
				// Get the first row to create the column headings

				$fp = fopen($filename, 'r');
				$frow = fgetcsv($fp);
				

				$ccount = 0;
				//Mention all the characters that needs to be replaced, here
				$patterns=array('/"/','/\'/');
				foreach($frow as $column) {
					$ccount++;
					if(isset($columns)) $columns .= ', ';
					else $columns="";
						$column = preg_replace($patterns, '', trim($column));
						$columns .= "`". "$column"."`". " varchar(50)";
				}

				$create = "create table  `$table` ($columns);";
				//echo $create;
				$mysqli->query($create);
				
				if($mysqli->error) :
				{
					var_dump($mysqli->error );
					$_SESSION['tablecheck']=$mysqli->error;
					$_SESSION['tablename']=$_POST["dataset"];
					header('Location: ../create_model.php');
					return;
				}
				endif;

				/********************************************************************************/
				// Import the data into the newly created table.

				$file = $_SERVER['CONTEXT_DOCUMENT_ROOT'].'webeda/upload/' . $new;
				
				$q_loaddata = "load data infile '$file' into table `$table` fields terminated by ',' ignore 1 lines";
				echo $q_loaddata;
				$mysqli->query($q_loaddata);
				
				
				$q_delete_blanks= "DELETE FROM `$table` WHERE t_int =''";
				echo $q_delete_blanks;
				$mysqli->query($q_delete_blanks);
				
				
				//SELECT `Model No.`, name_ind1,`rsquare` FROM other_21_20
				$q_get_col_count= "SELECT ((COUNT(*)-7)/3) as count FROM information_schema.columns WHERE table_name = '$table'";
				echo "<br></br>$q_get_col_count";
				$result=$mysqli->query($q_get_col_count);
				
				foreach($result as $row)
				{
					$count = stripslashes($row['count']);
				}
				$variable="TRIM(BOTH ',' FROM CONCAT(";
				for($i=1;$i<=$count;$i++)
				{
					if($variable == "TRIM(BOTH ',' FROM CONCAT(") :
						$variable = $variable."TRIM(name_ind".$i.")";
					else :
						$variable = $variable .",',',TRIM("."name_ind".$i.")";
					endif;
				}
				$variable = $variable.")) AS VALUE";
				
				echo "<br></br>$variable<br></br>";
				$q_insert_model= "INSERT INTO model_mapping(model_table, model_name,model_details,rsquare,eda_id,project_id,created_date,modified_date) SELECT '$table',`Model No.`,$variable,'rsquare',$Edaid, $Projectid,now(),now() FROM `$table`";
				echo $q_insert_model;
				$mysqli->query($q_insert_model);
							
				header('Location: ../models.php');
			//}
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