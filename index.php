<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
$_SESSION['tablecheck']="";
$_SESSION['tablename']="";

?>
<!DOCTYPE HTML>
<!--
	Alpha by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>M:Modeler by Madison Business Analytics</title>
				
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
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body class="landing">

			<!-- Header -->
			<header id="header" class="alt">
			<h1>
				<img src="images/EDA WT.png" id="logo" height="32" width="32" style="top:7px;position:relative;right=5px" />
			
				<a href="index.php">M:Modeler</a> by Madison Business Analytics
				
			</h1>
				</nav>
			</header>

			<!-- Banner -->
				<section id="banner">
					<h2>M:Modeler</h2>
					<p>Another fine tool by Madison Business Analytics.</p>
					<ul class="actions">
						<li><a href="Login.php" class="button special">Login</a></li>
						<li><a href="learn more.php" class="button">Learn More</a></li>
					</ul>
				</section>

			<!-- Main -->
				<section id="main" class="container">
			
					<section class="box special">
						<header class="major">
							<h2>Introducing the ultimate WEBTOOL
							<br />
							for analyzing your business</h2>
							<p>The EDA approach is precisely that--an approach--not a set of techniques, but an attitude/philosophy about how a data analysis should be carried out.</p>
						</header>
						<span class="image featured"><img src="images/pic01.jpg" alt="" /></span>
					</section>
							
					<section class="box special features">
						<div class="features-row">
							<section>
								<span class="icon major fa-bolt accent2"></span>
								<h3>Security</h3>
								<p>Powered by MD5 encryption methodology.</p>
							</section>
							<section>
								<span class="icon major fa-area-chart accent3"></span>
								<h3>Easy upload</h3>
								<p>Uploading of data cannot get anymore easier than this. Simply select a <i>.csv</i> file and upload.</p>
							</section>
						</div>
						<div class="features-row">
							<section>
								<span class="icon major fa-cloud accent4"></span>
								<h3>Exploratory Data Analysis</h3>
								<p>See what your data says by viewing the state of the art visual components.</p>
							</section>
							<section>
								<span class="icon major fa-lock accent5"></span>
								<h3>Models</h3>
								<p>Analyse the models graphically</p>
							</section>
						</div>
					</section>
			<!--			
					<div class="row">
						<div class="6u">

							<section class="box special">
								<span class="image featured"><img src="images/pic02.jpg" alt="" /></span>
								<h3>Sed lorem adipiscing</h3>
								<p>Integer volutpat ante et accumsan commophasellus sed aliquam feugiat lorem aliquet ut enim rutrum phasellus iaculis accumsan dolore magna aliquam veroeros.</p>
								<ul class="actions">
									<li><a href="#" class="button alt">Learn More</a></li>
								</ul>
							</section>
							
						</div>
						<div class="6u">

							<section class="box special">
								<span class="image featured"><img src="images/pic03.jpg" alt="" /></span>
								<h3>Accumsan integer</h3>
								<p>Integer volutpat ante et accumsan commophasellus sed aliquam feugiat lorem aliquet ut enim rutrum phasellus iaculis accumsan dolore magna aliquam veroeros.</p>
								<ul class="actions">
									<li><a href="#" class="button alt">Learn More</a></li>
								</ul>
							</section>

						</div>
					</div>

				</section>
				
			<!-- CTA 
				<section id="cta">
					
					<h2>Sign up for beta access</h2>
					<p>Blandit varius ut praesent nascetur eu penatibus nisi risus faucibus nunc.</p>
					
					<form>
						<div class="row uniform half collapse-at-2">
							<div class="8u">
								<input type="email" name="email" id="email" placeholder="Email Address" />
							</div>
							<div class="4u">
								<input type="submit" value="Sign Up" class="fit" />
							</div>
						</div>
					</form>
					
				</section> -->
				
			<!-- Footer -->
			<?php include 'includes/footer.php'; ?>
	</body>
</html>