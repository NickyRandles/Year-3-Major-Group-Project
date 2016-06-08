<?php
include_once("include/db_connect.php");
include_once("include/checkLoginStatus.php");
if($loggedIn == true){
	header("location: http://localhost:8080/Project/index.php");
	exit();
}
$accountUser = "";
$message = "";
$info = "";

if(isset($_GET["username"]) && isset($_GET["status"])){
	$accountUser = $_GET["username"];
	$status = $_GET["status"];
	if($status == "welcome"){
		$message = "<p id='message'>Welcome $accountUser, Please sign in for the first time!</p>";
	}
	else if($status == "wrongPassword"){
		$message = "<p id='message'>Incorrect password entered</p>";
		$sql = "SELECT * FROM users WHERE username = '$accountUser' LIMIT 1";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		
		$accountName = $row["username"];
		$firstName = $row["firstName"];
		$lastName = $row["lastName"];
		$profilePic = $row["profilePic"];
		
		$info = "<div id='info'>";
		if($profilePic != null){$info .= "<img src='$profilePic' alt='$accountName'>";}
		else{$info .= "<img src='images/blankProfile.jpg' alt='$accountName'>";}
		$info .= "<div>";
		$info .= "<p>$accountName</p>";
		$info .= "<p>$firstName $lastName</p>";
		$info .= "</div>";
		$info .= "<span><a href='login.php'><img src='images/x.png'>Not me</a></span>";
		$info .= "</div>";
	}
	
}
if(isset($_POST['submit'])){
	$username = "";
	
	if(isset($_POST["username"])){
		$username = $_POST['username'];
	}
	if($accountUser != null){
		$username = $accountUser;
	}
	$password = $_POST['password'];
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND userType != 'b'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows > 0){
		echo "Logged in";
	
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
		$query = mysql_query($sql);
		header("location: http://localhost:8080/Project/index.php");
	}
	
	else{
		$error = "Incorrect password or username";
	}

}
else{
	
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log in</title>
	<link rel="shortcut icon" href="images/icon.png"/>
	<link href="css/login.css" type="text/css" rel="stylesheet">
	<style>
		#loginForm{
			margin-top: 100px;
		}
		#message{
			text-align: center;
		}
		label{
			display: block;
			width: 20%;
			margin-top: 10px;
			float: left;
		}
		#info{
			height: 100px;
			width: 80%;
			float: left;
		}
		#info img{
			height: 100%;
			width: 30%;
			float: left;
		}
		#info div{
			margin-top: 20px;
		}
		#info p{
			margin: 5px;
			width: 40%;
			margin-left: 10%;
			float: left;
		}
		#info span{
			width: 30%;
			margin-left: 40%;
			float: left;		
		}
		#info span img{
			height: 16px;
			width: 16px;
		}
		#info span a{
			text-decoration: none;
			color: black;
		}
		#info span a:hover{
			text-decoration: underline;
		}
	</style>
</head>
<body>
<?php include_once("include/header.php") ?>
<div id="loginForm">

<?php
	echo $message;
	if($accountUser != null){
		echo "<form action='login.php?username=$accountUser&status=wrongPassword' method='post'>";
	}
	else{
		echo "<form action='login.php' method='post'>";
	}
?>
	<div>
		<label for="username">Username:</label>
		<?php
			if($info != null){
				echo $info;
			}
			else{
				echo "<input type='text' name='username'><br>";
			}
		?>
	</div>
	<div>
		<label for="password">Password:</label>
		<input type="password"= name="password"><br>
	</div>
	<input type="submit" value="Log in" name="submit">
</form>
</div>
</body>
</html>