<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();

?>
<html>
	<head>
        <title>Create Project</title>
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
    </head>
<body>

		 <?php if (login_check($mysqli) == true) : ?>
           <!-- Header -->
			<?php include 'includes/MainMenu.php'; ?>
			
		<section id="main" class="container">
			<header>
				<h2>Create Project</h2>
				<p>Please enter the name of the new project.</p>
			</header>
		<section class="box">
			<form action="includes/new_project.php" method="post" enctype="multipart/form-data">
			
			
				<div class="row uniform half collapse-at-2">
					<div class="8u">
						<input type="text" name="dataset" id="dataset" value="" placeholder="Project Name" />
					</div>	
					<div class="2u">
						<input type="submit" value="Submit" class="fit" />
					</div>
					<div class="2u">
						<a href="project.php" class="button alt">Cancel</a>
					</div>
				</div>
			
			
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