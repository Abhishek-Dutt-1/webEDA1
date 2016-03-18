
<!DOCTYPE html>


<html>
<head>
	
	<title>M:Modeler - Help</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="icon" href="favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.dropotron.min.js"></script>
	<script src="js/jquery.scrollgress.min.js"></script>
	<script src="js/skel.min.js"></script>
	<script src="js/skel-layers.min.js"></script>
	<script src="js/init.js"></script>-->
	<noscript>
		<link rel="stylesheet" href="css/skel.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/style-wide.css" />
	</noscript>
	
	<script type="text/JavaScript" src="js/sha512.js"></script> 
	<script type="text/JavaScript" src="js/forms.js"></script> 
		
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.dropotron.min.js"></script>
	<script src="js/jquery.scrollgress.min.js"></script>
	<script src="js/skel.min.js"></script>
	<script src="js/skel-layers.min.js"></script>
	<script src="js/init.js"></script>
	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css">
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
</head>
<body class="landing">
	<!-- Header -->
		<header id="header">
			<h1>
				<img src="images/EDA WT.png" id="logo" height="32" width="32" style="top:7px;position:relative;right=5px" />
				<a href="">M:Modeler</a> by Madison Business Analytics
			</h1>
			<nav id="nav">
				<ul>
					<li><a href="index.php">Home</a></li>
				</ul>
			</nav>
		</header>
		<section id="main" class="container"> 
			<header>
				<h2>Help</h2>
				<p>Just an assorted selection of elements.</p>
			</header>
			<div class="row">
				<div class="12u">
					<!-- Text -->
					<section class="box">
						<h3>M:Modeler - Help</h3>
						<div data-role="collapsible-set" >
							<div data-role="collapsible" data-collapsed="false">
								<h3>About M:Modeler Webtool</h3>
								<p>M:Modeler Webtool helps.....</p>
							</div>
							<div data-role="collapsible">
								<h3>Developers</h3>
								<p>Abhishek Dutt <br>
								   Sinu Joseph</p>
							</div>
							<div data-role="collapsible">
								<h3>Project</h3>
								You can create or select an existing project.<br>
								Projects are created to distinguish between different clients.
							</div>
							<div data-role="collapsible">
								<h3>EDA</h3>
								<p>You can create a new EDA or select an existing existing EDA.<br></p>
								<b>Create EDA:</b><br>
								When you click on the Create EDA option, it'll take you to the page to upload EDA data and to name it. <br>
								You need to enter the following details - 
									<li padding-left:5em>Enter a dataset Name (To distinguish between other datasets).</li>
									<li padding-left:5em>Browse and select the csv file which has EDA data.</li>
								
								<img src="images/help/upload_Eda.PNG" alt="Upload EDA" style="width:350px;height:180px" >
								
							</div>
							<div data-role="collapsible">
								<h3>KPI Charts</h3>
								<p>Trend Chart</p>
								
							</div>
							<div data-role="collapsible">
								<h3>EDA Charts</h3>
								EDA Charts has the following Charts <br>
								<ul>
									<li>Bivariates:</li>
									<li>Mean Difference:</li>
									<li>Diagnostics:</li>
								</ul>
							</div>
							<div data-role="collapsible">
								<h3>Analytics(Model)</h3>
								<p>Select or Upload a Model</p>
								You can select from the list of models or you can click on upload model to feed new models to the tool.
								<img src="images/help/select_model.PNG" alt="Select Model" style="width:500px;height:200px" ><br>
								<b>Upload new model</b>
								Provide the name of the model set and select a csv file to upload the data.<br>
								<img src="images/help/upload_model.PNG" alt="Upload Model" style="width:400px;height:200px" ><br>
							</div>
							<div data-role="collapsible">
								<h3>Model Charts</h3>
								Model Charts has the following charts<br>
								<li>Actual vs Predicted</li>
								<li>Contribution</li>
								<li>Sensitivity</li>
								<li>Saturation</li>
								<li>Simulation</li>
							</div>
						</div>
					</section>
				</div>
			</div>
		</section>		
			
			<!-- Footer -->
			<?php include 'includes/footer.php'; ?>
			
	

</body>
</html>
