<?php
include_once("../include/db_connect.php");

if(isset($_POST["reporter"]) && isset($_POST["reported"]) && isset($_POST["reason"]) && isset($_POST["explanation"])){
	$reporter = $_POST["reporter"];
	$reported = $_POST["reported"];
	$reason = $_POST["reason"];
	$explanation = htmlentities($_POST["explanation"]);
	
	$sql = "SELECT * FROM reports WHERE reporter = '$reporter' AND reported = '$reported' LIMIT 1";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows > 0){
		echo "alreadyReported";
		exit();
	}
	
	$sql = "INSERT INTO reports(reporter, reported, reason, explanation, reportTime) VALUES('$reporter', '$reported', '$reason', '$explanation', now())";
	$query = mysql_query($sql);
	echo "reportedSuccess";
	exit();
}

?>