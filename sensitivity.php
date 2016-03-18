<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>M:Modeler - Model</title>
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
		<script src="viz/Highcharts/js/highcharts.js"></script>	
		<script src="viz/Highcharts/js/modules/exporting.js"></script>
		<script type='text/javascript' charset='utf-8' src='viz/gristmill-jquery-popbox/popbox.js'></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
        <link rel="stylesheet" href="styles/main.css" />
		<link rel="stylesheet" href="viz/styles/charts.css" />		
		<link rel='stylesheet' href='viz/gristmill-jquery-popbox/popbox.css' type='text/css'>		
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

				<section class="box">
					<div class="breadCrumb">
						<a href="index.php">Home</a> &raquo; <a href="project.php">Projects</a> &raquo; <a href="eda.php">Data</a> &raquo; <a href="avp.php">Analytics</a> &raquo; <a href="sensitivity.php">Sensitivity</a>
					</div>
					<?php include 'viz/modelButtons.php' ?>

					<div style="clear: both;">
						<div id="chartContainer2">
							<h3 style="float:left;width:45%;">Sensitivity</h3>
							<div id="modelSelectDropdown">
								<div class="select-wrapper">
								<?php 
									$selectedModel = null; 
									if( isset($_GET['model']) ) {
										$selectedModel = $_GET['model'];
									}
								?>
								<form>
									<select name="model" onchange='this.form.submit()'>
										<?php 
												$userid=$_SESSION['user_id'];
												$Projectid=$_SESSION['projectid'];
												$Edaid=$_SESSION['edaId'];
												
												$q_GetProjects="SELECT m.id,m.model_name,m.model_details,m.rsquare FROM model_mapping m WHERE m.eda_id = $Edaid";		
												$result = $mysqli->query($q_GetProjects);
												foreach ( $result as $key => $row )
														{
														$modelid = stripslashes($row['id']);
														$modelname =  stripslashes($row['model_name']);	
														$modeldetails =  stripslashes($row['model_details']);	
														$modelrsquare =  stripslashes($row['rsquare']);	
														
										?>
														<option 
															value='<?php echo $modelid; ?>'
															<?php if( $selectedModel == null && $key == 0) { $selectedModel=$modelid ;echo 'selected'; } ?> 
															<?php if( $selectedModel == $modelid ) { echo 'selected'; } ?> 
														>
															<?php echo $modelname; ?>
														</option>
														<!--
															<input type="radio" title="<?php echo $modelid; ?>" id="<?php echo $modelid; ?>" name="modelid" value ="<?php echo $modelid; ?>" checked>
															<label for="<?php echo $modelid; ?>"><?php echo "<u>".$modelname."</u><b> Variables(</b> ".$modeldetails." <b>) RSquare:</b>".$modelrsquare; ?></label>
														-->

												<?php } ?>
									</select>
									<noscript><input type="submit" value="Submit"></noscript>
								</form>
								</div>
								<div id="modelSelectDropdownUploadNew">
									<a href="create_model.php" class="button small">Upload</a>
								</div>
							</div>
						</div>
						<div id="chartContainer3">
							<!--
							<div id="contribSeriesChart"></div>
							-->
							<div style="clear: both;"></div>
							<!--
							<div id="avContributionChartContainer">
								<h3>Average Contribution</h3>
								<div id="avContributionChart"></div>
							</div>
							-->
							<div id="senstivityChartContainer">

								<div id="senstivityChart"></div>
								
								
							</div>
							
							<div style="clear: both;"></div>
						
							<div id="senitivityPopbox">
								<div class="popboxContainer">
									<div class='popbox'>
										<a class='popboxOpen' href='#'>Options</a>
										<div class='popboxCollapse'>
											<div class='popboxBox'>
												<div class='popboxArrow'></div>
												<div class='popboxArrow-border'></div>
												<div style="margin: 25px;">
													<div id="sensitivityControls">
														<label for="investment">Investment Rs.</label><input type="text" id="investment" value=100>
														<label for="periodAveraged">Period Averaged</label><input type="text" id="periodAveraged" value=3>
														<div id="cprpInputContainer"></div>
														<a href="#" class="button alt small" id="updateSensitivityChart">Update</a>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div style="clear: both;"></div>
							
						</div>
					</div>
					
					
					
				</section>
			</section>
			<!-- Footer -->
			<?php include 'includes/footer.php'; ?>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
            </p>
        <?php endif; ?>
		
		<script type="text/javascript">var modelId = "<?php echo $selectedModel; ?>";</script>
		<script type="text/javascript">var edaId = "<?php echo $_SESSION['edaId']; ?>";</script>
		<script type="text/javascript">var projectId = "<?php echo $_SESSION['projectid']; ?>";</script>		
		<script src="viz/Sensitivity/sensitivity_charts.js"></script>
    </body>
</html>