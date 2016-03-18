<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>New User Registration</title>
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
		<link rel="stylesheet" href="css/style-register.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    <body>
		<header id="header">
			<h1><a href="index.php">M:Modeler</a> by Madison Business Analytics</h1>
			<nav id="nav">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="#" class="button">Register</a></li>
				</ul>
			</nav>
		</header>	
		<section id="banner">
			<section id="main" class="container">
			<!-- Registration form to be output if the POST variables are not
			set or if the registration script caused an error. -->
			<h2>Register with us</h2>
			<?php
			if (!empty($error_msg)) {
				echo $error_msg;
			}
			?>
			<center>
			<form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" 
					method="post" 
					name="registration_form" style="color:black;" align="middle">
					
					<div class="row uniform half">
						<div class="6u">
							<input type="text" name="username" id="username" value="" placeholder="User Name" />
						</div> 
						
					</div>
					
					<div class="row uniform half">
						<div class="6u">
							<input type="text" name="email" id="email" value="" placeholder="Email" />
						</div> 
					</div>
					<div class="row uniform half">
						<div class="6u">
							<input type="password" name="password" id="password" value="" placeholder="Password" />
						</div>
					</div>
					<div class="row uniform half">
						<div class="6u">
							<input type="password" name="confirmpwd" id="confirmpwd" value="" placeholder="Confirm password" />
						</div> 
					</div>
				
				
				<div class="row uniform">
					<div class="12u">
						<ul class="actions">
							<li><input type="button" 
								   value="Register" 
								   onclick="return regformhash(this.form,
												   this.form.username,
												   this.form.email,
												   this.form.password,
												   this.form.confirmpwd);" /> </li>
						   <li><input type="reset" value="Reset" class="alt" /></li>
						</ul>
					</div>
				</div>
			</form>
			</center>
			<p>Return to the <a href="login.php">login page</a>.</p>
			<div align="left">
			<ul>
				<li>Usernames may contain only digits, upper and lower case letters and underscores</li>
				<li>Emails must have a valid email format</li>
				<li>Passwords must be at least 6 characters long</li>
				<li>Passwords must contain
					<ul>
						<li>At least one upper case letter (A..Z)</li>
						<li>At least one lower case letter (a..z)</li>
						<li>At least one number (0..9)</li>
					</ul>
				</li>
				<li>Your password and confirmation must match exactly</li>
			</ul>
			</div>
			
			</section>
		</section>
		<!-- Footer -->
			<?php include 'includes/footer.php'; ?>
    </body>
</html>