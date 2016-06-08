<?php
include_once("../include/checkLoginStatus.php");
if($loggedIn != true){
	header("location: http://localhost:8080/Project/");
	exit();
}
if(isset($_POST["username"])){
	$username = $_POST["username"];
	$exists = false;
	$wrongLength = false;
	
	$sql = "SELECT username FROM users WHERE username = '$username' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows > 0){
		$exists = true;
	}
	if(strlen($username) < 5 || strlen($username) > 20){
		$wrongLength = true;
	}
	if($exists == true){
		echo "taken";
		exit();
	}
	else if($exists == false && $wrongLength == false){
		echo "notTaken";
		exit();
	}
	else if($wrongLength == true ){
		echo "wrongLength";
		exit();
	}
}
?>