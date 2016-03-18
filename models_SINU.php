<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();





?>
<!DOCTYPE html>
<html>
    <head>
        <title>Model</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
									<li><a href="create_model.php" class="button special">Upload a new Model</a></li>
								</ul>
							</section>
					</div>
				</div>
				<section class="box">
					<form action="includes/delete_model.php" method="post" name="delete_model" >
						<h3>Select a Model</h3>
						<table class="alt">
						<?php 
								$userid=$_SESSION['user_id'];
								$Projectid=$_SESSION['projectid'];
								$Edaid=$_SESSION['edaId'];
								
								$q_GetProjects="SELECT m.id,m.model_name,m.model_details,m.rsquare FROM model_mapping m WHERE m.eda_id = $Edaid";		
								$result = $mysqli->query($q_GetProjects);
								foreach ( $result as $row)
										{
										$modelid = stripslashes($row['id']);
										$modelname =  stripslashes($row['model_name']);	
										$modeldetails =  stripslashes($row['model_details']);	
										$modelrsquare =  stripslashes($row['rsquare']);	
										
						?>
										<tr><td class="4u">
										<input type="radio" title="<?php echo $modelid; ?>" id="<?php echo $modelid; ?>" name="modelid" value ="<?php echo $modelid; ?>" checked>
											<label for="<?php echo $modelid; ?>"><?php echo "<u>".$modelname."</u><b> Variables(</b> ".$modeldetails." <b>) RSquare:</b>".$modelrsquare; ?></label>
										</td> </tr>
								<?php } ?>
						</table>
						<input type="submit" class="button" onclick="submitForm('model_charts.php')" value="OK" name="Action">
						<input type="submit" class="button special" onclick="submitForm('delete_model.php')" value="Delete" name="Action">
						<a href="index.html" class="button alt">Cancel</a>
				
					</form>
				</section>
			</section>
			<?php include 'includes/footer.php'; ?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>