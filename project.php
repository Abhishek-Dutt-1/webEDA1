<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
$_SESSION['projectid']="";
$_SESSION['edaId']="";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>M:Modeler - Project</title>
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
				return confirm("Are you sure you wish to delete this Project?");
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
								<ul class="actions">
									<li><a href="create_project.php" class="button special">Create new Project</a></li>
								</ul>
								
							</section>

					</div>
				</div>
				
				<section class="box">
					<form action="includes/delete_project.php" method="post" name="form1" >
						<h3>Select a Project</h3>
						<table class="alt">
						<?php 
								$userid=$_SESSION['user_id'];
								$q_GetProjects="SELECT p.id,p.name FROM projects p, mapping_members_projects map, members m WHERE p.id = map.projects_id AND m.id = map.members_id AND m.id = '$userid'";				
								$result = $mysqli->query($q_GetProjects);
								foreach ( $result as $row)
										{
										$projectid = stripslashes($row['id']);
										$projectname =  stripslashes($row['name']);	
										
						?>
										<tr><td class="4u">
										<input type="radio" id="<?php echo $projectid; ?>" name="projectid" value ="<?php echo $projectid; ?>" checked>
											<label for="<?php echo $projectid; ?>"><?php echo $projectname; ?></label>
										</td> </tr>
								<?php } ?>
						</table>
						<input type="submit" class="button" onclick="submitForm('eda.php')" value="OK" name="Action">
						<input type="submit" class="button special" onclick="return confirm_delete();" onsubmit="return confirm('Are you sure you want to delete this project?');" value="Delete" name="Action">
						<a href="index.html" class="button alt">Cancel</a>
				
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