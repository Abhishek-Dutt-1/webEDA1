<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
		var_dump($_POST);
		
		$projectid = $_POST['project'];
		$brandname = $_POST['name'];
		$color=$_POST['setcolor'];
		$ignore_params = array('project', 'name','setcolor','Action');
		
		iF ($_POST["Action"] == "Assign Color") :
		{
			if($brandname == "" || $brandname == null):
			{
				echo "<div align='center'> Please mention a Brand Name";
				?>
					<br></br>
					<a href="javascript:history.go(-1)">Go Back</a> </html>
				<?php
				return false;
			}
			endif;
			$insert_value= "insert into `set_color_eda_model`(projectid,name,color,created_date,modified_date) values($projectid,'$brandname','$color',now(),now()) ";
			echo $insert_value;
			
			$mysqli->query($insert_value);
		}
		
		elseif ($_POST["Action"] == "Update") :
		{
			foreach ($_POST as $param_name => $param_val) {
				if(in_array($param_name, $ignore_params, true)) :
					echo  '<br>';
				else :
					$param_name = preg_replace( "[_]", "",$param_name);
					$update_value = "update `set_color_eda_model` set color='$param_val' where  projectid=$projectid and REPLACE(REPLACE(NAME,' ','' ),'_','')='$param_name'";
					//echo $update_value . '<br>';
					$mysqli->query($update_value);
					
				endif;
			}
			
		}
		elseif ($_POST["Action"] == "Delete") :
		{
			foreach ($_POST as $param_name => $param_val) {
				if($_POST[$param_name]=='on') :
					echo $_POST[$param_name].'<br>';
					$param_name = preg_replace( "[_]", "",$param_name);
					$delete_value = "delete from `set_color_eda_model` where  projectid=$projectid and REPLACE(REPLACE(NAME,' ','' ),'_','')='$param_name'";
					echo $delete_value . '<br>';
					$mysqli->query($delete_value);
				endif;
			}
		}
		endif;
		header('Location: ../color-selection.php');		
else : 
?>
		<html>
					<p>
						<span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
					</p>
		</html>
 <?php endif; 
?>