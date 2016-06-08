<?php
include_once("../include/db_connect.php");

if(isset($_POST["sender"]) && isset($_POST["reciever"]) && isset($_POST["message"])){
	$sender = $_POST["sender"];
	$reciever = $_POST["reciever"];
	$message = $_POST["message"];
	$sql = "INSERT INTO messages(sender, reciever, message, sendTime) VALUES('$sender', '$reciever', '$message', now())";
	$query = mysql_query($sql);
	
	$postIds = array();
	
	$sql = "SELECT id FROM messages WHERE sender = '$sender' AND reciever = '$reciever'";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query)){
		array_push($postIds, $row["id"]);
	}
	$sql = "SELECT id FROM messages WHERE sender = '$reciever' AND reciever = '$sender'";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query)){
		array_push($postIds, $row["id"]);
	}
	exit();
}


if(isset($_POST["log_username"]) && isset($_POST["username"]) && isset($_POST["message"])){
	$log_username = $_POST["log_username"];
	$username = $_POST["username"];
	$message = $_POST["message"];
	
	$sql = "SELECT id FROM conversations WHERE user1 = '$log_username' AND user2 = '$username'
	OR user1 = '$username' AND user2 = '$log_username' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows > 0){
		$sql = "INSERT INTO messages(sender, reciever, message, sendTime)
		VALUES('$log_username', '$username', '$message', now())";
		$query = mysql_query($sql);
		$sql = "UPDATE users SET messageCheck = now() WHERE username = '$log_username' LIMIT 1";
		$query = mysql_query($sql);
		echo "success";
	}
	else{
		$sql = "INSERT INTO conversations(user1, user2, startDate)
		VALUES('$log_username', '$username', now())";
		$query = mysql_query($sql);
		$sql = "INSERT INTO messages(sender, reciever, message, sendTime)
		VALUES('$log_username', '$username', '$message', now())";
		$query = mysql_query($sql);
		$sql = "UPDATE users SET messageCheck = now() WHERE username = '$log_username' LIMIT 1";
		$query = mysql_query($sql);
		echo "success";
	}
	
}
?>