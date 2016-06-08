<?php
include_once("../include/db_connect.php");
include_once("../include/checkLoginStatus.php");
if($loggedIn != true){
	exit();
}

if(isset($_POST["type"]) && isset($_POST["user"])){
	$type = $_POST["type"];
	$user = $_POST["user"];
	
	$sql = "SELECT id FROM users where username = '$user' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows < 1){
		echo "User no longer exists.";
		exit();
	}
	
	if($type == "block"){
		$sql = "SELECT id FROM blockedusers WHERE blocker = '$log_username' AND blockee = '$user' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows > 0){
			echo "You already have this user blocked";
			exit();
		}
		else{
			$sql = "INSERT INTO blockedusers(blocker, blockee, blockedDate) VALUES('$log_username' , '$user', now())";
			$query = mysql_query($sql);
			echo "blockSuccess";
			exit();
		}
	}
	else if($type == "unblock"){
		$sql = "SELECT id FROM blockedusers WHERE blocker = '$log_username' AND blockee = '$user' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows < 1){
			echo "User is already not blocked";
			exit();
		}
		else{
			$sql = "DELETE FROM blockedusers WHERE blocker = '$log_username' AND blockee = '$user' LIMIT 1";
			$query = mysql_query($sql);
			echo "unblockSuccess";
			exit();
		}
	}
}
?>