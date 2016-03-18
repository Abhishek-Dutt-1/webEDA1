<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();



?>
<!DOCTYPE html>
<html>
    <head>
        <title>EDA</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		
<!-- disable cache since firefox causes problems 
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
 end disable cache -->

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

		<link rel="stylesheet" href="viz/styles/charts.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    <body>
	
        <?php if (login_check($mysqli) == true) : ?>
            <!-- Header -->
			
			<?php include 'includes/MainMenu.php'; ?>
					
			<section id="main" class="container">
				<header  style="display: none;">
					<h2>EDA Charts</h2>
					<p>Select from the list of eda below.</p>
				</header>
				<div class="row">
					<div class="12u">

						<!-- Buttons -->
						<section class="box" id="chartContainer1">
							<div class="breadCrumb">
								<a href="index.php">Home</a> &raquo; <a href="protected_page.php">Projects</a> &raquo; <a href="eda.php">Data</a> &raquo; <a href="Charts.php">EDA</a> &raquo; <a href="Charts.php">Bivariates</a>
							</div>
							<?php include 'viz/chartButtons.php' ?>
							<div style="clear: both;">
								<div id="chartContainer2">
									<h3>Trend Chart</h3>
									<div id="trendChartContainer">
									</div>
									<h3>Bivariate Charts</h3>
									<div id="edaChartContainer">
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</section>
	
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="Login.php">login</a>.
            </p>
        <?php endif; ?>
		<script type="text/javascript">var edaId = "<?php echo $_SESSION['edaId']; ?>";</script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">google.load('visualization', '1.0', {'packages':['corechart']});</script>
		<script src="viz/TrendChart/trend_charts.js"></script>
		<script src="viz/eda/eda_charts.js"></script>
    </body>
</html>