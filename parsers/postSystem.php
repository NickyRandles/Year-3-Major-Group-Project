<?php
include_once("../include/db_connect.php");

if(isset($_POST["input"]) && isset($_POST["type"])){
	$input = $_POST["input"];
	$type = $_POST["type"];
	
	if($type == "friend"){
		$sql = "SELECT username, profilePic FROM users WHERE username LIKE '%$input%' LIMIT 10";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			$user = $row["username"];
			$image = $row["profilePic"];
			if($image != null){
				echo "<li><img src='$image'>$user</li>";
			}
			else{
				echo "<li><img src='images/blankProfile.jpg'>$user</li>";
			}
			
		}
	}
	else if($type == "group"){
		$sql = "SELECT name, image FROM groups WHERE name LIKE '%$input%' LIMIT 10";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			$name = $row["name"];
			$image = $row["image"];
			if($image != null){
				echo "<li><img src='$image'>$name</li>";
			}
			else{
				echo "<li><img src='images/notAvailable.jpg'>$name</li>";
			}
		}
	}
	
}

if(isset($_POST["type"]) && isset($_POST["initiator"]) && isset($_POST["name"]) && isset($_POST["message"]) && isset($_POST["link"])){
	$type = $_POST["type"];
	$initiator = $_POST["initiator"];
	$name = $_POST["name"];
	$message = $_POST["message"];
	$link = $_POST["link"];
	
	if($type == "friend"){
		$sql = "SELECT username FROM users WHERE username = '$name' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_fetch_array($query);
		
		if($numRows < 1){
			echo "$name does not exist";
			exit();
		}
		
		$sql = "INSERT INTO notifications(username, initiator, message, link, dateMade)
		VALUES('$name', '$initiator', '$message', '$link', now())";
		$query = mysql_query($sql);
		echo "addSuccess";
		exit();
	}
	else if($type == "group"){
		
		$sql = "SELECT id FROM groups WHERE name = '$name' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		$row = mysql_fetch_array($query);
		$groupId = $row["id"];
		if($numRows < 1){
			echo "Group does not exist!";
			exit();
		}
		
		$sql = "SELECT * FROM groupmembers WHERE groupId = '$groupId'";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows < 1){
			echo "Group has no members!";
			exit();
		}
		while($row = mysql_fetch_array($query)){
			$username = $row["memberName"];
			$sql = "INSERT INTO notifications(username, initiator, message, link, dateMade)
			VALUES('$username', '$initiator', '$message', '$link', now())";
			$query = mysql_query($sql);
			echo "addSuccess";
			exit();
		}
	}
	else{
		echo "Try again later!";
	}	
}

else if(isset($_POST["withdraw"]) && isset($_POST["withdraw"])){
	$id = $_POST["id"];
	$sql = "UPDATE items SET status = 'w' WHERE id = '$id' LIMIT 1";
	$query = mysql_query($sql);
	echo "withdrawn";
}

?>