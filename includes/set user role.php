<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
		//var_dump($_POST);
		//var_dump($_SESSION);
		
		$userid = $_POST['user'];
		$userrole = $_POST['role'];
		
		
		iF ($_POST["Action"] == "Change Role") :
		{
			if($userid == "" || $userid == null):
			{
				echo "<div align='center'> Please select a user";
				?>
					<br></br>
					<a href="javascript:history.go(-1)">Go Back</a> </html>
				<?php
				return false;
			}
			endif;
			$update_role= "UPDATE members SET role = $userrole WHERE id =$userid";
			//echo $update_role;
			$mysqli->query($update_role);
		}
		
		elseif ($_POST["Action"] == "Delete User") :
		{
			if($userid == "" || $userid == null):
			{
				echo "<div align='center'> Please select a user";
				?>
					<br></br>
					<a href="javascript:history.go(-1)">Go Back</a> </html>
				<?php
				return false;
			}
			endif;
			
			$delete_user = "delete from members where id=$userid";
			//echo $delete_user;
			$mysqli->query($delete_user);
		}
		endif;
		header('Location: ../user admin access.php');		
else : 
?>
		<html>
					<p>
						<span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
					</p>
		</html>
 <?php endif; 
?>