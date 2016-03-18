<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'includes/correl.php';
 
sec_session_start();

$EDAid= $_SESSION['edaId'];

?>
<!DOCTYPE HTML>
<!--
	Alpha by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Correlation</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="icon" href="favicon.ico" type="image/x-icon"> 
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrollgress.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="js/editablegrid-2.0.1.js"></script>   
		<!-- I use jQuery for the Ajax methods -->
		<script src="js/jquery-1.7.2.min.js" ></script>
		<script src="js/eda_col_map.js" ></script>
		<script type="text/javascript">
			window.onload = function() { 
				//datagrid = new DatabaseGrid();
			}; 
		</script>
		<script>
			function myFunction(col1,col2) {
				alert(col1);
				alert(col2);
			}
		</script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<script>
			function showgrid(str) {
				datagrid = new DatabaseGrid(str);
			}
			
			function showeda(str) {
			  document.getElementById("tablecontent").innerHTML="";
			  if (str=="") {
				document.getElementById("get_eda").innerHTML="";
				
				return;
			  } 
			  if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp1=new XMLHttpRequest();
			  } else { // code for IE6, IE5
				xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			  xmlhttp1.onreadystatechange=function() {
				if (xmlhttp1.readyState==4 && xmlhttp1.status==200) {
				  document.getElementById("get_eda").innerHTML=xmlhttp1.responseText;
				  
				}
			  }
			  xmlhttp1.open("GET","includes/get_eda.php?q="+str,true);
			  xmlhttp1.send();
			}
		</script>
		
	</head>
	<body>
	  <?php if (login_check($mysqli) == true) : ?>
            <!-- Header -->
			
			<?php include 'includes/MainMenu.php'; ?>
			
			<!-- Main -->
			<section id="main" class="container">
				<!-- Check whether the EDA is selected. if not then send back to select the eda-->
				<?php
				if($_SESSION['edaId']==null || $_SESSION['edaId'] == "") :
				{ ?>
				<section class="box">
					<p>
						<span class="error">Oops!! </span> Please <a href="eda.php">Select a EDA Dataset</a>.
					</p>
				</section>
				<?php return;
				}
				endif; ?>
							
					
				<div class="row">
					<div class="12u">
						<!-- Text -->
						<section class="box">
							<?php include 'viz/compareButtons.php' ?>
							<div style="clear: both;">
								<div id="container2">
									<h3>KPI - Driver Correlation</h3>
									
								</div>
							</div>
							
							
							
							<?php
								//Get the values
								$q_Variables="SELECT eda.tablename,map.brand,map.`column name`,map.variable,map.variable_type,map.ownership FROM eda_column_mapping map, eda_dataset eda WHERE map.edaid=eda.id AND edaid = $EDAid ORDER BY ownership DESC,variable DESC";				
								$result = $mysqli->query($q_Variables);
								
								foreach ( $result as $row) 
								{
									if(stripslashes($row['variable'])=='Driver') :
									{
										$Driver[] = stripslashes($row['column name']);
										$variable_type_temp[] = stripslashes($row['variable_type']);
									}
									endif;
									
									$brand_temp[] = stripslashes($row['brand']);
									$tablename = stripslashes($row['tablename']);
								}
								$brand=array_unique($brand_temp);
								
								//Get the data for the above table
								$q_getValues="select * from `$tablename`";
								$correl_values = $mysqli -> query($q_getValues);
								
								foreach ( $brand as $brand_value) 
								{
									foreach ( $result as $row) 
									{
										if((stripslashes(($row['variable'])=='KPI')) && (stripslashes(($row['brand'])==$brand_value))) :
										{
											$KPI[] = stripslashes($row['column name']);
										}
										endif;
										
									}	
									
									//var_dump($variable_type_temp);
									
									
									
									
							?>
							<h3> <?php print $brand_value; ?></h3>
							<div class="row uniform half">
								<div class="6u" style="width:100%; overflow-y: scroll; overflow-x: scroll; height:500px;">
									<table class="alt" >
										<tr>
											<td></td>
											<?php 	
											
												$temp="";
												$count=0;
												$lastelement=count($variable_type_temp);
												$previousvalue="";
												
												foreach($variable_type_temp as $variable)
												{
													
													if ($previousvalue=="") :
													{
														//$temp = $variable;
														$previousvalue=$variable;
														$count = $count +1;
													}
													elseif($variable==$previousvalue) :
													{
														$count=$count+1;
														if($lastelement == 1) :
														{	 
														?>
														
														<th colspan= "<?php echo ($count) ?>" style="text-align:center"><?php echo $variable  ?></th>
											<?php	}
														endif;
													}
													
													else :
													{
														//echo $previousvalue. $count; ?>
														<th colspan= "<?php echo ($count) ?>" style="text-align:center"><?php echo $previousvalue  ?></th>
											<?php	$previousvalue=$variable; 
														if($lastelement == 1) :
															{	 
															?>
															
															<th colspan= "<?php echo ($count) ?>" style="text-align:center"><?php echo $variable  ?></th>
												<?php	}
															endif;
														$count=1;	
													}
													endif;
													$lastelement = $lastelement - 1;
													//echo $variable."<br>";
												}
												
											?>
											
										</tr>										
										<tr>
											<td align="middle" >KPI <br>vs <br>Driver Correlation</td>
											<?php 	foreach ($Driver as $driver_value)
																{
																	?> <th><?php echo $driver_value  ?></th>	
													<?php	} ?>
										</tr>
										<?php 	foreach ($KPI as $kpi_value)
													{
														?> <tr><th><?php echo $kpi_value  ?></th>
														<?php 	foreach ($Driver as $driver_value)
																	{
																		foreach ( $correl_values as $row) 
																		{
																			$array1[] = stripslashes($row[$kpi_value]);
																			$array2[] = stripslashes($row[$driver_value]);
																		}
																		$correlation=0;
																		$correlation = round(Correlation($array1, $array2),2);
																		unset($array1);
																		unset($array2);
																		echo '<td style="background-color:'.Correlation_color($correlation).'">';
																		//
																		echo '<a href="#" onclick="myFunction(\''. $kpi_value.'\',\''. $driver_value.'\')">';
																		echo $correlation;
																		echo '</a>';
																		//print Correlation_color($correlation);
																		
																		?></td>	
														<?php	} ?>
															</tr>
										<?php	} 
										?>

									</table>
								</div>		
							</div>
							<hr>
							<?php
								
									unset($KPI);
								}
							?>	
							
						</section>
					</div>
				</div>
			</section>
			<!-- Footer -->
			<?php include 'includes/footer.php'; ?>		
							
			    <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>