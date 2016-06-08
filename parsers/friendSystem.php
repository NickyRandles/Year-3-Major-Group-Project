<?php
include_once("../include/db_connect.php");
include_once("../include/checkLoginStatus.php");
if($loggedIn != true){
	exit();
}

if(isset($_POST["type"]) && isset($_POST["user"])){
	$type = $_POST["type"];
	$user = $_POST["user"];
	
	$sql = "SELECT username FROM users WHERE username = '$user' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows < 1){
		echo "Sorry this user no longer exists";
		exit();
	}
	
	if($type == "friend"){
		$sql = "SELECT id FROM blockedusers WHERE blocker = '$log_username' AND blockee = '$user' LIMIT 1";
		$query = mysql_query($sql);
		$numRows1 = mysql_num_rows($query);
		
		$sql = "SELECT id FROM blockedusers WHERE blocker = '$user' AND blockee = '$log_username' LIMIT 1";
		$query = mysql_query($sql);
		$numRows2 = mysql_num_rows($query);
		
		$sql = "SELECT id FROM friends WHERE asker = '$log_username' AND asked = '$user' AND accepted = 'y' 
				OR asker = '$user' AND asked = '$log_username' AND accepted = 'y' LIMIT 1";
		$query = mysql_query($sql);
		$numRows3 = mysql_num_rows($query);
				
		$sql = "SELECT id FROM friends WHERE asker = '$log_username' AND asked = '$user' AND accepted = 'n' LIMIT 1";
		$query = mysql_query($sql);
		$numRows4 = mysql_num_rows($query);
		
		$sql = "SELECT id FROM friends WHERE asker = '$user' AND asked = '$log_username' AND accepted = 'n' LIMIT 1";
		$query = mysql_query($sql);
		$numRows5 = mysql_num_rows($query);
		
		if($numRows1 > 0){
			echo "You have this users blocked.";
			exit();
		}
		else if($numRows2 > 0){
			echo "Sorry you are blocked by this user.";
			exit();
		}
		else if($numRows3 > 0){
			echo "You are already friends.";
			exit();
		}
		else if($numRows4 > 0){
			echo "You have already sent a friend request. It is still pending";
			exit();
		}
		else if($numRows5 > 0){
			echo "$user has already requested your friendship. Check your notifications.";
			exit();
		}
		else{
			$sql = "INSERT INTO friends(asker, asked, dateMade) VALUES('$log_username', '$user', now())";
			$query = mysql_query($sql);
			echo "friendSuccess";
			exit();
		}
	}
	
	else if($type == "unfriend"){
		$sql = "SELECT id FROM friends WHERE asker = '$log_username' AND asked = '$user' AND accepted = 'y' LIMIT 1";
		$query = mysql_query($sql);
		$numRows1 = mysql_num_rows($query);
		
		$sql = "SELECT id FROM friends WHERE asker = '$user' AND asked = '$log_username' AND accepted = 'y' LIMIT 1";
		$query = mysql_query($sql);
		$numRows2 = mysql_num_rows($query);
		
		if($numRows1 > 0){
			$sql = "DELETE FROM friends WHERE asker = '$log_username' AND asked = '$user' AND accepted = 'y' LIMIT 1";
			$query = mysql_query($sql);
			echo "unfriendSuccess";
			exit();
		}
		else if($numRows2 > 0){
			$sql = "DELETE FROM friends WHERE asker = '$user' AND asked = '$log_username' AND accepted = 'y' LIMIT 1";
			$query = mysql_query($sql);
			echo "unfriendSuccess";
			exit();
		}
		else{
			echo "This friendship does not exist in the database.";
			exit();
		}
	}
}

if(isset($_POST["action"]) && isset($_POST["requestId"]) && isset($_POST["asker"])){
	$action = $_POST["action"];
	$requestId = $_POST["requestId"];
	$asker = $_POST["asker"];
	
	$sql = "SELECT id FROM users WHERE username = '$asker' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows < 1){
		echo "User no longer exists"; 
		exit();
	}
	
	if($action == "accept"){
		$sql = "SELECT id FROM friends WHERE asker = '$asker' AND asked = '$log_username' AND accepted = 'y' 
				OR asker = '$log_username' AND asked = '$asker' AND accepted = 'y'";
		$query = mysql_query($sql);
		$friendCount = mysql_num_rows($query);
		if($friendCount > 0){
			echo "You are already friends";
			exit();
		}
		else{
			$sql = "UPDATE friends SET accepted = 'y' WHERE id = '$requestId' AND asker = '$asker' AND asked = '$log_username' LIMIT 1";
			$query = mysql_query($sql);
			echo "acceptSuccess";
			exit();
		}
	}
	
	else if($action == "decline"){
		$sql = "DELETE FROM friends WHERE id = '$requestId' AND asker = '$asker' AND asked = '$log_username' AND accepted = 'n' LIMIT 1";
		$query = mysql_query($sql);
		echo "declineSuccess";
		exit();
	}
	
	
	
	
}	
?>





