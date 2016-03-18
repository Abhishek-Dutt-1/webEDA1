<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
		//var_dump($_POST);
		$EDAid = $_POST['edaid'];
		
		iF ($_POST["Action"] == "OK") :
		{
			//Get the EDA Name
			$select_edaname= "SELECT datasetname FROM eda_dataset WHERE id =  $EDAid ";
			$result = $mysqli->query($select_edaname);
			if($result->num_rows > 0) 
				{
					while($row = $result->fetch_assoc()) 
					{
						$datasetname =  stripslashes($row['datasetname']);	
					}
				}
			//Get Start and End Date for the selected EDA
			//Get the column name from schema table
			//Get Min of Date using Limit
			//Get Max of Date by adding row num first then using limit
			$get_col_name= "SELECT column_name,table_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = (SELECT tablename FROM eda_dataset WHERE id =$EDAid) AND ordinal_position = 1";
			$colresult = $mysqli->query($get_col_name);
			if($colresult->num_rows > 0) 
				{
					while($row = $colresult->fetch_assoc()) 
					{
						$column_name =  stripslashes($row['column_name']);
						$table_name =  stripslashes($row['table_name']);						
					}
				}
			
			$get_start_date='SELECT `'. $column_name .'` FROM `'.$table_name.'` LIMIT 1';
			$startdateresult = $mysqli->query($get_start_date);
			
			if($startdateresult->num_rows > 0) 
				{
					while($row = $startdateresult->fetch_assoc()) 
					{
						$MinDate =  stripslashes($row[$column_name]);	
					}
				}
			
			$get_end_date='SELECT `'. $column_name .'` FROM (SELECT `'. $column_name .'`,@rownum:=@rownum+1 AS ROW FROM  `'.$table_name.'` , (SELECT @rownum:=0) a) a ORDER BY ROW DESC LIMIT 1';
			$enddateresult = $mysqli->query($get_end_date);
			
			if($enddateresult->num_rows > 0) 
				{
					while($row = $enddateresult->fetch_assoc()) 
					{
						$MaxDate =  stripslashes($row[$column_name]);	
					}
				}
			$DateRange = 'From ' . $MinDate . ' To ' . $MaxDate;
			
			$_SESSION['EDADatePeriod']=$DateRange;
			$_SESSION['selectedEDA']=$datasetname;
			$_SESSION['edaId'] = $EDAid;
			header('Location: ../kpi.php');
		}
		
		elseif ($_POST["Action"] == "Delete") :
		{
			 echo $EDAid;
			if ($EDAid==null || $EDAid=="")
			{
			echo "Please Select a EDA to Delete";
			header('Location: ../eda.php');
			return false;
			}
			
			$select_table= "SELECT tablename FROM eda_dataset WHERE id =  $EDAid ";
			$result = $mysqli->query($select_table);
			
			if($result->num_rows > 0) 
				{
					while($row = $result->fetch_assoc()) 
						{
						$tablename =  stripslashes($row['tablename']);	
					}
				}
				else {
					echo "NO RESULTS";	
					}
				
			$delete_table = "drop table `$tablename`";
			$mysqli->query($delete_table);

			$delete_eda = "delete from eda_dataset where id = $EDAid";
			$mysqli->query($delete_eda);
			
			$delete_eda_col_mapping = "delete from eda_column_mapping where edaid = $EDAid";
			$mysqli->query($delete_eda_col_mapping);
			
			
			header('Location: ../eda.php');
		
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