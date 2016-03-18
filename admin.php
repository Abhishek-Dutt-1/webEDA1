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
		<title>Admin Panel</title>
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
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<script>
			function showUser(str) {
			  if (str=="") {
				document.getElementById("txtHint").innerHTML="";
				return;
			  } 
			  if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			  } else { // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			  xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				  document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
				}
			  }
			  xmlhttp.open("GET","includes/getuserprojects.php?q="+str,true);
			  xmlhttp.send();
			}
		</script>

		
	</head>
	<body>
	  <?php if (login_check($mysqli) == true) : ?>
            <!-- Header -->
			
			<?php include 'includes/MainMenu.php'; ?>
			
			<!-- Main -->
			<section id="main" class="container">
				<header>
					<h2>Admin Panel</h2>
					<p>Grant or Revoke access to users</p>
				</header>
				<div class="row">
					<div class="12u">

						<!-- Text -->
							<section class="box">
								<form method="post" action="includes/grant_revoke.php">
									<h3>Please select the user and Project to grant/revoke access to the user</h3>
									<hr />
									<div class="row uniform half">
										<div class="8u">
											<div class="select-wrapper">
											
												<select name="user" id="user" onchange="showUser(this.value)">
													<option value="">- User -</option>
													<?php
														$q_GetUsers="SELECT m.id,m.username FROM  members m";				
														$result = $mysqli->query($q_GetUsers);
														foreach ( $result as $row) 
																{
																$username = stripslashes($row['username']);
																$userid = stripslashes($row['id']);
																?><option value=<?php echo $userid;?>><?php echo $username;?></option><?php
																}
													?>
												</select>
											</div>
											<br>
											
											<div class="select-wrapper">
												<select name="project" id="project">
													<option value="">- Project -</option>
													<?php
														$q_GetProjects="SELECT p.id,p.name FROM  projects p";				
														$result = $mysqli->query($q_GetProjects);
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
											<div class="row uniform">
												<div class="12u">
													<ul class="actions">
														<li><input type="submit" value="Grant Access" class="special" name="Action"/></li>
														<li><input type="submit" value="Revoke Access" class="alt" name="Action" /></li>
													</ul>
												</div>
											
									</div>
									<br>
									<div id="txtHint"></b></div>
										</div>
										
									</div>
								</form>
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