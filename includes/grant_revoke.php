<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
		var_dump($_POST);
		$userid = $_POST['user'];
		$projectid = $_POST['project'];
		
		iF ($_POST["Action"] == "Grant Access") :
		{
			$insert_value= "insert into mapping_members_projects(members_id,projects_id, created_date,modified_date) values($userid,$projectid,now(),now()) ";
			echo $insert_value;
			$mysqli->query($insert_value);
		}
		
		elseif ($_POST["Action"] == "Revoke Access") :
		{
			$delete_value = "delete from mapping_members_projects where members_id = $userid and projects_id=$projectid";
			echo $delete_value;
			$mysqli->query($delete_value);
		}
		endif;
		header('Location: ../admin.php');		
else : 
?>
		<html>
					<p>
						<span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
					</p>
		</html>
 <?php endif; 
?>