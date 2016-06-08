<?php
session_start();
include_once("db_connect.php");
$loggedIn = false;
$admin = false;
$log_id = '';
$log_username = '';
$log_password = '';


function evalLoggedUser($id, $username, $password){
	$sql = "SELECT * FROM users WHERE id = '$id' AND username = '$username' AND password = '$password'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows > 0){
		return true;
	}
}

function checkIfAdmin($id, $username, $password){
	$sql = "SELECT * FROM users WHERE id = '$id' AND username = '$username' AND password = '$password' AND userType = 'a'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);

	if($numRows > 0){
		return true;
	}
}

if(isset($_SESSION['userId']) && isset($_SESSION['username']) && isset($_SESSION['password'])){
	$log_id = $_SESSION['userId'];
	$log_username = $_SESSION['username'];
	$log_password = $_SESSION['password'];
	
	$loggedIn = evalLoggedUser($log_id, $log_username, $log_password);
	$admin = checkIfAdmin($log_id, $log_username, $log_password);
}

else if(isset($_COOKIE['userId']) && isset($_COOKIE['username']) && isset($_COOKIE['password'])){
	$_SESSION['userId'] = $_COOKIE['userId'];
	$_SESSION['username'] = $_COOKIE['username'];
	$_SESSION['password'] = $_COOKIE['password'];
	
	$log_id = $_SESSION['userId'];
	$log_username = $_SESSION['username'];
	$log_password = $_SESSION['password'];
	
	$loggedIn = evalLoggedUser($log_id, $log_username, $log_password);
	$admin = checkIfAdmin($log_id, $log_username, $log_password);
}

if($loggedIn == true){
	$sql = "UPDATE users SET lastLogin = now() WHERE id = '$log_id'";
	mysql_query($sql);
}
?>
