<?php
include_once("../include/db_connect.php");
if(isset($_POST["option"]) && $_POST["option"] == "ban"){
	if(isset($_POST["username"])){
		$username = $_POST["username"];
		$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows < 1){
			echo "User no longer exists";
			exit();
		}
		
		$sql = "SELECT * FROM users WHERE username = '$username' and userType = 'b' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows > 0){
			echo "Already banned";
			exit();
		}
		
		$sql = "UPDATE users SET userType = 'b' WHERE username = '$username' LIMIT 1";
		$query = mysql_query($sql);
		echo "bannedSuccess";
		exit();
	}
}
else if(isset($_POST["option"]) && $_POST["option"] == "unban"){
	if(isset($_POST["username"])){
		$username = $_POST["username"];
		$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows < 1){
			echo "User no longer exists";
			exit();
		}
		
		$sql = "SELECT * FROM users WHERE username = '$username' and userType = 'r' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows > 0){
			echo "Already unbanned";
			exit();
		}
		
		$sql = "UPDATE users SET userType = 'r' WHERE username = '$username' LIMIT 1";
		$query = mysql_query($sql);
		echo "unbannedSuccess";
		exit();
	}
}

if(isset($_POST["reportId"])){
	$reportId = $_POST["reportId"];
	
	$sql = "SELECT * FROM reports WHERE id = '$reportId' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows < 1){
		echo "alreadyDeleted";
		exit();
	}
	
	$sql = "DELETE FROM reports WHERE id = '$reportId' LIMIT 1";
	$query = mysql_query($sql);
	echo "postDeleted";
	exit();
	
}
?>








