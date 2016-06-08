<?php
include_once("include/checkLoginStatus.php");
if($loggedIn == true){
	header("location: http://localhost:8080/Project/index.php");
	exit();
}

$logErrors = "";

if(isset($_POST['loginSubmit'])){
	$username = $_POST['logUsername'];
	$password = $_POST['logPassword'];
	
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND userType != 'b'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows > 0){
		$row = mysql_fetch_array($query);
		$userId = $row['id'];
		$username = $row['username'];
		$password = $row['password'];
		
		$_SESSION['userId'] = $userId;
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		setcookie('userId', $userId, strtotime('+30 days'));
		setcookie('username', $username, strtotime('+30 days'));
		setcookie('password', $password, strtotime('+30 days'));
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$sql = "UPDATE users SET ip = '$ip_address', lastLogin = now() WHERE username = '$username' LIMIT 1";
		mysql_query($sql);
		header("location: http://localhost:8080/Project/index.php");
		exit();
	}
	
	$sql = "SELECT username FROM users WHERE username = '$username' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows > 0){
		header("location: http://localhost:8080/Project/login.php?username=$username&status=wrongPassword");
		exit();
	}
	
	else{
		$logErrors .= "<p class='error'>Incorrect login details entered</p>";
	}

}
$regErrors = "";
$username = "";
$firstName = "";
$lastName = "";
$email = "";
if(isset($_POST['registerSubmit'])){	
	$username = $_POST['username'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirmPassword = $_POST['confirmPassword'];
	$gender = $_POST['gender'];
	$city = $_POST['city'];
	$county = $_POST['county'];
	$ip_address = $_SERVER['REMOTE_ADDR']; 

	$reg1 = "/^[a-zA-Z0-9]+$/";
	$reg2 = "/^[a-zA-Z]+$/";
	$reg3 = "/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/";
	
	if($password != $confirmPassword){
		$regErrors .=  "<li class='error'>Passwords do not match</li>";	
	}
	if(empty($username)){
		$regErrors .=  "<li class='error'>Please fill in your username</li>";
	}
	else if(!preg_match($reg1, $username)){
		$regErrors .=  "<li class='error'>Username must be characters and numbers only</li>";
	}
	if(empty($firstName)){
		$regErrors .=  "<li class='error'>Please fill in your first name</li>";
	}
	else if(!preg_match($reg2, $firstName)){
		$regErrors .=  "<li class='error'>First name must be characters only</li>";
	}
	if(empty($lastName)){
		$regErrors .=  "<li class='error'>Please fill in your last name</li>";
	}
	else if(!preg_match($reg2, $lastName)){
		$regErrors .=  "<li class='error'>Last name must be characters only</li>";
	}
	if(empty($email)){
		$regErrors .=  "<li class='error'>Please fill in your email</li>";
	}
	else if(!preg_match($reg3, $email)){
		$regErrors .=  "<li class='error'>Email address in wrong format <br>(Format: someone@example.com)</li>";
	}
	if(empty($password)){
		$regErrors .=  "<li class='error'>Please fill in your password</li>";
	}
	if($regErrors == ""){
		$sql = "INSERT INTO users(username, firstName, lastName, email, password, gender, city, county, ip, signUp, lastLogin, messageCheck) 
				VALUES('$username', '$firstName', '$lastName', '$email', '$password', '$gender', '$city', '$county', '$ip_address', now(), now(), now())";
		$query = mysql_query($sql) or die("problem");
		
		if(!file_exists("users/$username")){
			mkdir("users/$username");
		}
		header("location: login.php?username=$username&status=welcome");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Shop and Save</title>
	<link rel="shortcut icon" href="images/icon.png"/>
	<link href="css/search.css" type="text/css" rel="stylesheet">
	<script src="jquery/jquery-1.11.2.min.js"></script>
	<script src="jquery/jquery-ui.min.js"></script>
	<link href="jquery/jquery-ui.min.css" type="text/css" rel="stylesheet">
	<style>
		body{
			font-family: "Lucida Console", Monaco, monospace;
		}
		
		header img{
			height: 17%;
			width: 50%;
			float: left;
			margin-bottom: 25px;
		}
		
		#community{
			width: 40%;
			height: 400px;
			margin-left: 5%;
			background: url("images/welcome.png");
			background-size: 100% 100%;
			float: left;
		}
		
		form label{
			font-size: 20px;
		}
		form input{
			font-size: 16px;
		}
		form select{
			font-size: 16px;
		}
		
		#loginForm{
			background-color: #409ede;
			padding: 10px;
			width: 45%;
			float: right;
			margin-top: 5%;
		}
		
		#loginForm div{
			width: 60%;
			margin: 0 auto;
			margin-bottom: 5px;
		}			
		
		#loginForm input[type=submit]{
			border-radius: 5px;
			margin-left: 50%;
		}
		
		#registerForm{
			float: right;
			width: 42%;
			margin-top: 7%;
			background-color: #8181F7;
			padding: 2%;
		}
				
		#registerForm h2{
			text-align: center;
		}
		
		#registerForm label{
			display: block;
			width: 50%;
			float: left;
		}
		
		#registerForm input[type=text], #registerForm input[type=password]{
			width: 40%;
			float: left;
			margin-top: -3px;
		}
				
		#registerForm input[type=submit]{
			width: 25%;
			margin-left: 50%;
			margin-top: 5px;
			padding: 5px;
			border-radius: 5px;
		}
		
		#registerForm div{
			width: 80%;
			margin: 0 auto;
			overflow: auto;
			padding-top: 10px;
			clear: both;
		}
		
		#registerForm div p{
			width: 100%;
			padding-top: 10px;
			color: cyan;
			text-align: center;
		}
		
		#registerForm div span{
			display: block;
			width: 22px;
			height: 22px;
			float: left;
			margin-top: -3px;
		}
		
		#registerForm div img{
			width: 100%;
			height: 100%;
		}
		
		.error{
			padding: 0;
			margin: 0;
			margin-bottom: 5px;
			color: #D40707;
		}
		
	</style>
	<script>
		function checkInput(id){
			var input = document.getElementById(id).value;
			var password = document.getElementById("password").value;
			$.post("parsers/formValidation.php", {id: id, password: password, input: input}, function(data){
				if(id == "username"){
					$("#" + id + "Span").html("<img src='images/ajax.gif'>");
					if(data == "usernameOk"){
						$("#" + id + "Span").html("<img src='images/tick.png'>");
						$("#" + id + "P").html("Username is Ok");
					}
					else if(data == "usernameTaken"){
						$("#" + id + "Span").html("<img src='images/error.png'>");
						$("#" + id + "P").html("Username is taken");
					}
					else if(data == "usernameInvalid"){
						$("#" + id + "Span").html("<img src='images/error.png'>");
						$("#" + id + "P").html("Username must be characters and letters only");
					}
					else if(data == "usernameWrongLength"){
						$("#" + id + "Span").html("<img src='images/error.png'>");
						$("#" + id + "P").html("Username must be 4 - 10 characters");
					}	
				}
				else if(id == "firstName"){
					$("#" + id + "Span").html("<img src='images/ajax.gif'>");
					if(data == "firstNameOk"){
						$("#" + id + "Span").html("<img src='images/tick.png'>");
						$("#" + id + "P").html("First Name is Ok");
					}
					else if(data == "firstNameInvalid"){
						$("#" + id + "Span").html("<img src='images/error.png'>");
						$("#" + id + "P").html("First name must be characters and letters only");
					}
				}
				else if(id == "lastName"){
					$("#" + id + "Span").html("<img src='images/ajax.gif'>");
					if(data == "lastNameOk"){
						$("#" + id + "Span").html("<img src='images/tick.png'>");
						$("#" + id + "P").html("Last Name is Ok");
					}
					else if(data == "lastNameInvalid"){
						$("#" + id + "Span").html("<img src='images/error.png'>");
						$("#" + id + "P").html("Last name must be characters and letters only");
					}
				}
				else if(id == "email"){
					$("#" + id + "Span").html("<img src='images/ajax.gif'>");
					if(data == "emailOk"){
						$("#" + id + "Span").html("<img src='images/tick.png'>");
						$("#" + id + "P").html("Email is Ok");
					}
					else if(data == "emailInvalid"){
						$("#" + id + "Span").html("<img src='images/error.png'>");
						$("#" + id + "P").html("Email address in wrong format <br>(Format: someone@example.com)");
					}
				}
				else if(id == "confirmPassword"){
					$("#" + id + "Span").html("<img src='images/ajax.gif'>");
					if(data == "passwordOk"){
						$("#" + id + "Span").html("<img src='images/tick.png'>");
						$("#" + id + "P").html("Passwords match");
					}
					else if(data == "passwordInvalid"){
						$("#" + id + "Span").html("<img src='images/error.png'>");
						$("#" + id + "P").html("Passwords do not match");					
					}
				}
			});
		}
		function populate(){
			var category = document.getElementById("county").value;
			var subcategory = document.getElementById("city");
			subcategory.innerHTML = "";
			
			if(category == "choose"){
				var optionArray = ["|Choose city", "|Choose county first"];
			}			
			else if(category == "Dublin"){
				var optionArray = ["|Choose City", "Blanchardstown|Blanchardstown", "CastleKnock|CastleKnock"];
			}
			else if(category == "Meath"){
				var optionArray = ["|Choose City", "Ratoath|Ratoath", "Maynooth|Maynooth"];
			}

			for(var option in optionArray){
				var values = optionArray[option].split("|");
				var newOption = document.createElement("option");
				newOption.value = values[0];
				newOption.innerHTML = values[1];
				subcategory.options.add(newOption);
			}
		}
	</script>
</head>
<body>
<header>
	<a href="index.php"><img src="images/header.jpg" alt="Shop and Save" id="home"></a>
</header>	
<div id="loginForm">
	<form action="" method="post">
		<?php echo $logErrors; ?>
		<div>
			<label for="username">Username:</label>
			<input type="text"= name="logUsername">
		</div>
		<div>
			<label for="password">Password:</label>
			<input type="password"= name="logPassword">
		</div>
		<input type="submit" value="Log in" name="loginSubmit">
	</form>
</div>
<div id="registerForm">
	<ol>
		<?php echo $regErrors; ?>
	</ol>
	<h2>Sign Up and Connect</h2>
	<form action="" method="post">
		<div>
			<label for="username">Username:</label>
			<input type="text"= name="username" id="username" value="<?php echo $username; ?>" onkeyup="checkInput(this.id)">
			<span id="usernameSpan"></span>
			<p id="usernameP"></p>
		</div>
		<div>
			<label for="firstName">First Name:</label>
			<input type="text"= name="firstName" id="firstName" value="<?php echo $firstName; ?>" onkeyup="checkInput(this.id)">
			<span id="firstNameSpan"></span>
			<p id="firstNameP"></p>
		</div>
		<div>	
			<label for="lastName">Last Name:</label>
			<input type="text"= name="lastName" id="lastName" value="<?php echo $lastName; ?>" onkeyup="checkInput(this.id)">
			<span id="lastNameSpan"></span>
			<p id="lastNameP"></p>
		</div>
		<div>	
			<label for="email">Email Address:</label>
			<input type="text"= name="email" id="email" value="<?php echo $email; ?>" onkeyup="checkInput(this.id)">
			<span id="emailSpan"></span>
			<p id="emailP"></p>
		</div>
		<div>	
			<label for="password">Password:</label>
			<input type="password" name="password" id="password">
		</div>
		<div>	
			<label for="confirmPassword">Confirm Password:</label>
			<input type="password" name="confirmPassword" id="confirmPassword" onkeyup="checkInput(this.id)">
			<span id="confirmPasswordSpan"></span>
			<p id="confirmPasswordP"></p>
		</div>
		<div>	
			<label for="gender">Gender:</label>
				<input type="radio" name="gender" value="m" checked="checked" id="male">Male
				<input type="radio" name="gender" value="f" id="female">Female
		</div>
		<div>
			<label>County:</label>
			<select name="county" id="county" onchange="populate()">
				<option value="choose">Choose County</option>
				<option value="Dublin">Dublin</option>
				<option value="Meath">Meath</option>
			</select>
		</div>
		<div>
			<label>City:</label>
			<select name="city" id="city">
				<option value="">Choose City</option>
				<option value="">Choose County first</option>
			</select>
		</div>
		<div>
			<input type="submit" value="Register" name="registerSubmit">
		</div>
	</form>
</div>
<div id="community">
</div>
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