<?php
include_once("../include/db_connect.php");

if(isset($_POST["searchItem"]) && isset($_POST["searchType"])){
	$search = $_POST["searchItem"];
	$type = $_POST["searchType"];
	
	if($type == "item"){
		$sql = "SELECT itemName FROM items WHERE itemName LIKE '$search%' AND status = 'a' LIMIT 10";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			echo "<li>".$row["itemName"]."</li>";
		}
	}
	else if($type == "person"){
		$sql = "SELECT username FROM users WHERE username LIKE '%$search%' LIMIT 10";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			echo "<li>".$row["username"]."</li>";
		}
	}
	else if($type == "group"){
		$sql = "SELECT name FROM groups WHERE name LIKE '%$search%' LIMIT 10";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			echo "<li>".$row["name"]."</li>";
		}
	}
	
	
}
?>