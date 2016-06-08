<?php
include_once("../include/checkLoginStatus.php");

if(isset($_POST["oldPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmPassword"])){
	$oldPassword = $_POST["oldPassword"];
	$newPassword = $_POST["newPassword"];
	$confirmPassword = $_POST["confirmPassword"];
	
	$changeErrors = "";
	$sql = "SELECT password FROM users WHERE username = '$log_username'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows > 0){
		$row = mysql_fetch_array($query);
		$currentPassword = $row["password"];
		
		$reg1 = "/^[a-zA-Z0-9]+$/";
		
		if(empty($oldPassword) && empty($confirmPassword) && empty($confirmPassword)){
			$changeErrors .= "<p id='error'>Please fill in all fields</p>";
		}	
		else if(!preg_match($reg1, $oldPassword) && !preg_match($reg1, $newPassword) && !preg_match($reg1, $confirmPassword)){
			$changeErrors .= "<p id='error'>All fields must be numbers and letters only</p>";
		}
		else{
			if($oldPassword != $currentPassword){
				$changeErrors .= "<p id='error'>Please enter your current password correctly</p>";
			}
			if($newPassword != $confirmPassword){
				$changeErrors .= "<p id='error'>Your passwords don't match</p>";
			}			
		}

		if($changeErrors == ""){
			$sql = "UPDATE users SET password = '$newPassword' WHERE username = '$log_username' LIMIT 1";
			$query = mysql_query($sql);
			$_SESSION["password"] = $newPassword;
			echo "<p>Your password has been successfully change</p>";
		}
	}
	else{
		$changeErrors .= "<p id='error'>Try again later</p>";
	}
	
	echo $changeErrors;
}

if(isset($_POST["password"]) && isset($_POST["reason"])){
	$password = $_POST["password"];
	$reason = $_POST["reason"];

	if($reason == ""){
		echo "<p id='error'>*Please give us a reason</p>";
	}
	
	if($password == ""){
		echo "<p id='error'>*Please fill in password field</p>";
	}
	
	if($reason == "" || $password == ""){
		exit();
	}
	
	$sql = "SELECT password FROM users WHERE username = '$log_username' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	$row = mysql_fetch_array($query);
	
	if($numRows > 0){
		$userPassword = $row["password"];
		if($password == $userPassword){
			$sql = "INSERT INTO closedaccounts(username, reason, dateClosed) VALUES('$log_username', '$reason', now())";
			$query = mysql_query($sql);
			
			$sql = "DELETE FROM users WHERE username = '$log_username' LIMIT 1";
			$query = mysql_query($sql);
			echo "accountClosed";
			exit();
		}
		else{
			echo "<p id='error'>*Your password is not correct!</p>";
		}	
	}
}

?>