<!DOCTYPE html>
<html>
<head>
</head>
<body>
<header>
	<a href="index.php"><img src="images/header.jpg" alt="Shop and Save" id="home"></a>
	<a href="signUpLogIn.php" id="login">Sign Up / Log In</a>
	<?php include_once("headerBasics.php"); ?>
</header>
<script>
	$("#home").mouseover(function() { 
		$(this).attr("src", "images/homepage.jpg");
	});
	$("#home").mouseout(function() {
		$(this).attr("src", "images/header.jpg");
	});
</script>
</body>
</html>