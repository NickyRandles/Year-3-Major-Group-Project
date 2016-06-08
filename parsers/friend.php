<?php
include_once("../include/checkLoginStatus.php");
if(isset($_POST["friend"])){
	$friend = $_POST["friend"];
	
	$sql = "SELECT id FROM friends WHERE asker = '$log_username' AND asked = '$friend' AND accepted = 'y'
			OR asker = '$friend' AND asked = '$log_username' AND accepted = 'y'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows > 0){
		$sql = "DELETE FROM friends WHERE asker = '$log_username' AND asked = '$friend' AND accepted = 'y'
			OR asker = '$friend' AND asked = '$log_username' AND accepted = 'y'";
		$query = mysql_query($sql);
		echo "unfriendSuccessful";
		exit();
	}
	else{
		echo "friendshipEndedAlready";
		exit();
	}
}
?>