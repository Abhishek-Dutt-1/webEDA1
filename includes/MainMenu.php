<header id="header">
	<h1>
		<img src="images/EDA WT.png" id="logo" height="32" width="32" style="top:7px;position:relative;right=5px" />
		<a href="index.php">M:Modeler</a> by Madison Business Analytics
	</h1>
	
	
	<nav id="nav">
		<ul>
			<?php if (isset($_SESSION['username'])) :
					echo "<li> Welcome! ".$_SESSION['username']."</li>";
					endif;?>
			<li><a href="index.php">Home</a></li>
			<li>
				<a href="" class="icon fa-angle-down">Go To</a>
				<ul>
					<li><a href="">EDA</a></li>
					<li><a href="">Model</a></li>
					<li><a href="contact.html">Contact</a></li>
				</ul>
			</li>
			<?php 
				$role=$_SESSION['role'];
				if($role==1) :
				{ ?>
			<li>
				<a href="" class="icon fa-angle-down">Admin</a>
				<ul>
					<li><a href="admin.php">User-Project Mapping</a></li>
					<li><a href="eda_col_map.php">EDA Column Mapping</a></li>
					<li><a href="color-selection.php">Color Selection</a></li>
					<li><a href="user admin access.php">User Access</a></li>
				</ul>
			</li>
			<?php	} 
				endif; ?>
			<li><a href="includes/logout.php" class="button">Log Out</a></li>
		</ul>
	</nav>
	<br></br>
	
</header>

<!-- <header id="header">
	<h1><a href="index.html">EDA WEBTOOL</a> by Madison Business Analytics</h1>
	<nav id="nav">
		<ul>
			<?php if (isset($_SESSION['username'])) :
					echo "<li> Welcome! ".$_SESSION['username']."</li>";
					endif;?>
			<li><a href="index.html">Home</a></li>
			<li>
				<a href="" class="icon fa-angle-down">Go To</a>
				<ul>
					<li><a href="generic.html">Generic</a></li>
					<li><a href="contact.html">Contact</a></li>
					<li><a href="elements.html">Elements</a></li>
					<li>
						<a href="">EDA</a>
						<ul>
							<li><a href="#">Trend Chart</a></li>
							<li><a href="#">EDA Charts</a></li>
							<li><a href="#">Diagnostic Charts</a></li>
							<li><a href="#">Mean Difference Charts</a></li>
						</ul>
					</li>
					<li>
						<a href="">Model</a>
						<ul>
							<li><a href="#">Contribution Charts</a></li>
							<li><a href="#">Sensitivity Charts</a></li>
							<li><a href="#">Saturation Curves</a></li>
							<li><a href="#">Actual vs Predicted</a></li>
							<li><a href="#">Contribution Series</a></li>
							<li><a href="#">Simulation</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<?php 
				$role=$_SESSION['role'];
				if($role==1) :
				{ ?>
				<li>
					<a href="admin.php">Admin</a>
				</li>
			<?php	} 
				endif; ?>
			<li><a href="includes/logout.php" class="button">Log Out</a></li>
		</ul>
	</nav>
	
</header> -->