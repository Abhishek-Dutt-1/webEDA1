<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
		var_dump($_POST);
		$modelid = $_POST['modelid'];
		
		iF ($_POST["Action"] == "OK") :
		{
			header('Location: ../Model_Charts.php');
		}
		elseif ($_POST["Action"] == "Delete") :
		{
			 echo $modelid;
			if ($modelid==null || $modelid=="")
			{
			echo "Please Select a Model to Delete";
			header('Location: ../models.php');
			return false;
			}
			
			$delete_model= "delete FROM model_mapping WHERE id =  $modelid ";
			echo $delete_model;
			$mysqli->query($delete_model);
			
			
			header('Location: ../models.php');
		
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