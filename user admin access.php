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
		<title>User Role</title>
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
			
			function showUserRole(str) {
				
			  if (str=="") {
				document.getElementById("get_user_role").innerHTML="";
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
				  document.getElementById("get_user_role").innerHTML=xmlhttp.responseText;
				  
				}
			  }
			  xmlhttp.open("GET","includes/get_user_role.php?q="+str,true);
			  
			  xmlhttp.send();
			  console.log(str);
			}
			
		</script>
		
		<script type="text/javascript">
			function confirm_delete() {
				return confirm("Are you sure you wish to delete this user?");
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
								<form method="post" action="includes/set user role.php">
									<h3>Set the User Role</h3>
									<hr />
									<div class="row uniform half">
										<div class="8u">
											<div class="select-wrapper">
											
												<select name="user" id="user" onchange="showUserRole(this.value)">
													<option value="">- Select the User -</option>
													<?php
														$q_GetUsers="SELECT id,username,role FROM members WHERE username <> 'admin'";				
														$result = $mysqli->query($q_GetUsers);
														foreach ( $result as $row) 
																{
																$UserName = stripslashes($row['username']);
																$UserId = stripslashes($row['id']);
																?><option value=<?php echo $UserId;?>><?php echo $UserName;?></option><?php
																}
													?>
												</select>
											</div>
											<br>
											
											<div class="row uniform">
												<div class="8u">
														<div id="get_user_role"></div>
												</div>
											</div>
											<br>
											<div class="row uniform">
												<div class="12u">
													<ul class="actions">
														<li><input type="submit" value="Change Role" name="Action" class="button special"/></li>
														<li><input type="submit" value="Delete User" onclick="return confirm_delete();" name="Action" /></li>
														<li><input type="reset" value="Cancel" class="alt" name="Action" /></li>
													</ul>
												</div>
											
											</div>
									
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