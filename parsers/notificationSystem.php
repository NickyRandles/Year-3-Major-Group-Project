<?php
include_once("../include/checkLoginStatus.php");

if(isset($_POST["initiator"]) && isset($_POST["dateMade"])){
	$initiator = $_POST["initiator"];
	$dateMade = $_POST["dateMade"];

	$sql = "UPDATE notifications SET seen = 'y' WHERE initiator = '$initiator' AND dateMade = '$dateMade' LIMIT 1";
	$query = mysql_query($sql) or die("failed");
	echo "updated";
	
}
?>