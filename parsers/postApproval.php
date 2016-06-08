<?php
include_once("../include/db_connect.php");
include_once("../include/checkLoginStatus.php");
if($loggedIn != true){
	exit();
}

if(isset($_POST["postId"]) && isset($_POST["postChoice"])){
	$id = $_POST["postId"];
	$choice = $_POST["postChoice"];
	
	if($choice == "approve"){
		$sql = "SELECT * FROM items WHERE id = '$id' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows < 1){
			echo "Item no longer exists";
			exit();
		}
		$sql = "UPDATE items SET status = 'a' WHERE id = '$id' LIMIT 1";
		$query = mysql_query($sql);
		echo "approved";
		exit();
	}
	else if($choice == "disapprove"){
		$sql = "SELECT * FROM items WHERE id = '$id' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows < 1){
			echo "Item no longer exists";
			exit();
		}
		$sql = "UPDATE items SET status = 'w' WHERE id = '$id' LIMIT 1";
		$query = mysql_query($sql);
		echo "disapproved";
		exit();
	}
}
?>