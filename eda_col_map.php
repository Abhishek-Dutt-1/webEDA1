<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();

		

?>
<!DOCTYPE HTML>
<!--
	Alpha by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>EDA Data Column Mapping</title>
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
				
				<div class="row">
					<div class="12u">
						<!-- Text -->
						<section class="box">
						
							<h3>Select Project-EDA and Update the mapping</h3>
							<hr />
							<div class="row uniform half">
								<div class="6u">
									<div class="select-wrapper">
										<select name="project" id="project" onchange="showeda(this.value)">
											<option value="">- Project -</option>
											<?php
												$q_GetUsers="SELECT p.id,p.name FROM  projects p";				
												$result = $mysqli->query($q_GetUsers);
												foreach ( $result as $row) 
													{
													$projectname = stripslashes($row['name']);
													$projectid = stripslashes($row['id']);
													?><option value=<?php echo $projectid;?>><?php echo $projectname;?></option><?php
													}
											?>
										</select>
									</div>
									<br>
									<div class="row uniform half">
										<div class="12u">
												<div id="get_eda"></div>
										</div>
									</div>
									<br>
								</div>		
								
							</div>
								
							<div id="tablecontent">
							</div>
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