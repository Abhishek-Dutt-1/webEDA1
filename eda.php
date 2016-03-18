<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
$_SESSION['tablecheck']="";
$_SESSION['tablename']="";
$_SESSION['selectedEDA']="";
$_SESSION['EDADatePeriod']="";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>EDA WEBTOOL - EDA</title>
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
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
        <link rel="stylesheet" href="styles/main.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
		<script>
			function submitForm(action)
			{
				document.getElementById('form1').action = action;
				document.getElementById('form1').submit();
			}
		</script>
		<script type="text/javascript">
			function confirm_delete() {
				return confirm("Are you sure you wish to delete that EDA?");
			}
		</script>
    </head>
    <body>
	
        <?php if (login_check($mysqli) == true) : ?>
            <!-- Header -->
			<?php include 'includes/MainMenu.php'; ?>
			
			<section id="main" class="container">
				<div class="row">
					<div class="12u">
						<!-- Buttons -->
							<section class="box">
								<?php
								if($_SESSION['projectid']==null || $_SESSION['projectid'] == "") :
								{ ?>
									<p>
										<span class="error">Oops!! </span> Please <a href="Project.php">Select Project</a>.
									</p>
								<?php return;
								}
								endif; ?>
								<ul class="actions">
									<li><a href="create_eda.php" class="button special">Create new EDA dataset</a></li>
								</ul>
							</section>
					</div>
				</div>
				<section class="box">
					<form action="includes/delete_eda.php" method="post" name="delete_eda" >
						<h3>Select a EDA dataset</h3>
						<table class="alt">
						<?php 
								$userid=$_SESSION['user_id'];
								$Projectid=$_SESSION['projectid'];
								
								
								$q_GetProjects="SELECT e.id,e.datasetname FROM eda_dataset e WHERE e.projects_id = $Projectid";		
								$result = $mysqli->query($q_GetProjects);
								foreach ( $result as $row)
										{
										$edaid = stripslashes($row['id']);
										$edaname =  stripslashes($row['datasetname']);	
										
						?>
										<tr><td class="4u">
										<input type="radio" id="<?php echo $edaid; ?>" name="edaid" value ="<?php echo $edaid; ?>" checked>
											<label for="<?php echo $edaid; ?>"><?php echo $edaname; ?></label>
										</td> </tr>
								<?php } ?>
						</table>
						<input type="submit" class="button" onclick="submitForm('eda.php')" value="OK" name="Action">
						<input type="submit" class="button special" onclick="return confirm_delete();" value="Delete" name="Action">
						<a href="project.php" class="button alt">Cancel</a>
				
					</form>
				</section>
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