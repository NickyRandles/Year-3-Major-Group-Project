<?php
include_once("checkLoginStatus.php");
if($loggedIn == true){
	include_once("loggedInHeader.php");
}
else{
	include_once("loggedOutHeader.php");
}
?>
