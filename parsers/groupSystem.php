<?php 
include_once("../include/db_connect.php");

if(isset($_POST["groupName"]) && isset($_POST["groupDesc"]) && isset($_POST["creator"])){
	$groupName = $_POST["groupName"];
	$groupDesc = $_POST["groupDesc"];
	$creator = $_POST["creator"];
	$date = date('Y/m/d H:i:s');
	
	$sql = "INSERT INTO groups(name, description, creator, dateCreated) VALUES('$groupName', '$groupDesc', '$creator', '$date')";
	$query = mysql_query($sql);
	
	$sql = "SELECT id FROM groups WHERE name = '$groupName' AND description = '$groupDesc' AND creator = '$creator' AND dateCreated = '$date' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	$groupId = $row["id"];
	
	$sql = "INSERT INTO groupmembers(groupId, memberName, memberType) VALUES('$groupId', '$creator', 'm')";
	$query = mysql_query($sql);
	echo "<p><a href='http://localhost:8080/Project/group.php?id=$groupId'>Go to $groupName</a></p>";
}

if(isset($_POST["name"])){
	$name = $_POST["name"];
	
	$sql = "SELECT * FROM users WHERE username LIKE '%$name%' LIMIT 10";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query)){
		$user = $row["username"];
		echo "<li>$user</li>";
	}
}

if(isset($_POST["groupId"]) && isset($_POST["groupName"]) && isset($_POST["addUser"])){
	$groupId = $_POST["groupId"];
	$groupName = $_POST["groupName"];
	$user = $_POST["addUser"];
		
	$sql = "SELECT id FROM users WHERE username = '$user' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows < 1){
		echo "userDoestNoExist";
		exit();
	}
	
	$sql = "SELECT id FROM groupmembers WHERE groupId = '$groupId' AND memberName = '$user' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows > 0){
		echo "memberAlready";
		exit();
	}
	
	$sql = "INSERT INTO groupmembers(groupId, memberName, memberType) VALUES('$groupId ', '$user', 'r')";
	$query = mysql_query($sql);
	echo "addSuccess";
	exit();
}
if(isset($_POST["initator"]) && isset($_POST["username"]) && isset($_POST["message"])){
	$initator = $_POST["initator"];
	$username = $_POST["username"];
	$message = $_POST["message"];
	
	$sql = "INSERT INTO notifications(username, initiator, message, dateMade) VALUES('$username', '$initator', '$message', now())";
	$query = mysql_query($sql);
	echo "addSuccess";
	exit();
}

if(isset($_POST["groupId"]) && isset($_POST["poster"]) && isset($_POST["comment"])){
	$groupId = $_POST["groupId"];
	$poster = $_POST["poster"];
	$comment = $_POST["comment"];
	$time = date('Y/m/d H:i:s');
	
	$sql = "INSERT INTO groupposts(groupId, poster, comment, postTime) VALUES('$groupId', '$poster', '$comment', '$time')";
	$query = mysql_query($sql);
	
	$sql = "SELECT id FROM groupposts WHERE groupId = '$groupId' AND poster = '$poster'
	AND comment = '$comment' AND postTime = '$time' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	$postId = $row["id"];
	
	$sql = "SELECT * FROM groupposts WHERE groupId = '$groupId' ORDER by postTime ASC";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	$posts = "";
	$posts .= "<div class='posts' id='post$postId'>";
	$posts .= "<h3><a href='user.php?u=$poster'>$poster</a></h3>";
	$posts .= "<span>$time</span>";
	$posts .= "<p>$comment</p>";
	$posts .= "<div id='replies$postId'>";
	$posts .= "</div>";
	$posts .= "<input type='text' id='replyMessage' placeholder='Write a comment' onkeyup=\"writeReply('$postId', '$poster')\">";
	$posts .= "</div>";
	
	echo $posts;
	
}	


if(isset($_POST["postId"]) && isset($_POST["replier"]) && isset($_POST["message"])){
	$postId = $_POST["postId"];
	$replier = $_POST["replier"];
	$message = $_POST["message"];

	$sql = "INSERT INTO groupreplies(postId, replier, comment, replyTime) VALUES('$postId', '$replier', '$message', now())";
	$query = mysql_query($sql);
	
	$posts = "";
	$sql = "SELECT * FROM groupreplies WHERE postId = '$postId'";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query)){
		$replier = $row["replier"];
		$replyTime = $row["replyTime"];
		$reply = $row["comment"];
		
		$posts .= "<div class='replies'>";
		$posts .= "<h3><a href='user.php?u=$replier'>$replier</a></h3>";
		$posts .= "<span>$replyTime</span>";
		$posts .= "<p>$reply</p>";
		$posts .= "</div>";	
	}
	echo $posts;
}

if(isset($_POST["action"]) && isset($_POST["groupId"]) && isset($_POST["memberName"])){
	$action = $_POST["action"];
	$groupId = $_POST["groupId"];
	$memberName = $_POST["memberName"];
	
	if($action == "mod"){
		$sql = "UPDATE groupmembers SET memberType = 'm' WHERE groupId='$groupId' AND memberName = '$memberName' LIMIT 1";
		$query = mysql_query($sql);
		echo "modded";
		exit();
	}
	
	else if($action == "unmod"){
		$sql = "UPDATE groupmembers SET memberType = 'r' WHERE groupId='$groupId' AND memberName = '$memberName' LIMIT 1";
		$query = mysql_query($sql);
		echo "unmodded";
		exit();
	}	
}

if(isset($_POST["memberName"]) && isset($_POST["groupId"]) && isset($_POST["modAction"])){
	$memberName = $_POST["memberName"];
	$groupId = $_POST["groupId"];
	$modAction = $_POST["modAction"];
	
	if($modAction == "leave"){
		$sql = "DELETE FROM groupmembers WHERE groupId = '$groupId' AND memberName = '$memberName'";
		$query = mysql_query($sql);
		echo "leaveSuccess";
		exit();
	}
	else if($modAction == "close"){
		$sql = "DELETE FROM groups WHERE id = '$groupId'";
		$query = mysql_query($sql);
		
		$sql = "DELETE FROM groupmembers WHERE groupId = '$groupId'";
		$query = mysql_query($sql);
		
		$sql = "DELETE FROM groupposts WHERE groupId = '$groupId'";
		$query = mysql_query($sql);
		
		$sql = "SELECT id FROM groupposts WHERE groupId = '$groupId' LIMIT 1";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$postId = $row["id"];
		
		$sql = "DELETE FROM groupreplies WHERE postId = '$postId'";
		$query = mysql_query($sql);
		
		echo "closeSuccess";
		exit();
	}
}















?>
