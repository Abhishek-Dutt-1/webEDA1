<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
	{
		$fname= $_POST["dataset"];
		 //echo $fname;
		if ($fname==null || $fname=="")
		{
		echo "<div align='center'> Please mention the Project Name"; ?>
			<br></br>
			<a href="javascript:history.go(-1)">Go Back</a> </html>
			<?php
			return false;
		}
		//Write a database check for project name if exists
		$q_CheckProjectName="select name from projects where name = '$fname'";				
		$result = $mysqli->query($q_CheckProjectName);
		if($result->num_rows > 0) 
		{
			echo "<div align='center'> Project Name Already Exists!!"; ?>
			<br></br>
			<a href="javascript:history.go(-1)">Go Back</a> </html>
			<?php
			return false;
		}
		
		$q_insert_projects= "insert into projects(name,  created_date,modified_date) values ('$fname',now(),now())";
		$mysqli->query($q_insert_projects);
		
		$q_selectProjectid="select id from projects where name = '$fname'";				
		$result = $mysqli->query($q_selectProjectid);
		$projectid=0;
		$userid=$_SESSION['user_id'];
		if($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
				{
				$projectid =  stripslashes($row['id']);	
			}
		}
		else {
			echo "NO RESULTS";	
			}
		
		$q_Pro_Mem_Map="insert into mapping_members_projects (members_id,projects_id,created_date,modified_date) values ($userid,$projectid ,now(),now())";
		echo $q_Pro_Mem_Map;
		$mysqli->query($q_Pro_Mem_Map);
		header('Location: ../project.php');
		
		  
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