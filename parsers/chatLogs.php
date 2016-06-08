<?php
sleep(1);
include_once("../include/db_connect.php");
if(isset($_GET["log"]) && isset($_GET["user"])){
	$log = $_GET["log"];
	$user = $_GET["user"];
	
	$sql = "UPDATE users SET messageCheck = now() WHERE username = '$log' LIMIT 1";
	$query = mysql_query($sql);

	$postIds = array();
	
	$sql = "SELECT id FROM messages WHERE sender = '$log' AND reciever = '$user'";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query)){
		array_push($postIds, $row["id"]);
	}
	$sql = "SELECT id FROM messages WHERE sender = '$user' AND reciever = '$log'";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query)){
		array_push($postIds, $row["id"]);
	}
	
	asort($postIds);
	$sql = "SELECT profilePic from users WHERE username = '$log' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	$logPic = $row["profilePic"];
	$sql = "SELECT profilePic from users WHERE username = '$user' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	$userPic = $row["profilePic"];
	
	$messages = "";
	$messages .= "<div id='messageBox'>";
	foreach($postIds as $postId){
		$sql = "SELECT * FROM messages WHERE id = '$postId'";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$sender = $row["sender"];
		$message = $row["message"];
		$time = date("F j, Y, g:i a", strtotime($row["sendTime"]));
		$messages .= "<div id='eachMessage'>";
		if($sender == $log){
			if($logPic != null){
				$messages .= "<img src='$logPic' alt='$sender'>";
			}
			else{
				$messages .= "<img src='images/blankProfile.jpg' alt='$sender'>";
			}
		}
		else if($sender == $user){
			if($userPic != null){
				$messages .= "<img src='$userPic' alt='$sender'>";
			}
			else{
				$messages .= "<img src='images/blankProfile.jpg' alt='$sender'>";
			}			
		}
		$messages .= "<p id='time'>$time</p>";
		$messages .= "<p id='sent'>$sender says $message</p>";
		$messages .= "</div>";
	}
	$messages .= "</div>";
	echo $messages;
	exit();
}
?>