<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
		var_dump($_POST);
		$Projectid = $_POST['projectid'];
		
		iF ($_POST["Action"] == "OK") :
		{
			$_SESSION['projectid']=$_POST['projectid'];
			header('Location: ../eda.php');
		}
		elseif ($_POST["Action"] == "Delete") :
		{
			 echo $Projectid;
			if ($Projectid==null || $Projectid=="")
			{
			echo "Please Select a Project to Delete";
			return false;
			}
			//First Delete all tables created under this projectid
			
			$select_tables="SELECT DISTINCT tablename FROM eda_dataset WHERE projects_id = $Projectid UNION SELECT DISTINCT model_table FROM model_mapping WHERE project_id =$Projectid";
			$result = $mysqli->query($select_tables);
			foreach ( $result as $row)
				{
					$tablename = stripslashes($row['tablename']);
					$drop_tables = "drop table `$tablename`";
					echo "<br>".$drop_tables;
					$mysqli->query($drop_tables);
				}
			$eda_dataset="delete from eda_dataset where projects_id=$Projectid";
			$mysqli->query($eda_dataset);
			
			$model_mapping="delete from model_mapping where project_id=$Projectid";
			$mysqli->query($model_mapping);
			
			
			$delete_mapping = "delete from mapping_members_projects where projects_id = $Projectid";
			$mysqli->query($delete_mapping);
			
			$delete_projects = "delete from projects where id = $Projectid";			
			$mysqli->query($delete_projects);
			
			header('Location: ../project.php');
		
		}
		endif;
		
else : 
?>
		<html>
					<p>
						<span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
					</p>
		</html>
 <?php endif; 
?>