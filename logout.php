<?php 
session_start();

session_destroy();

setcookie('userId', '', strtotime('-5 days'));
setcookie('username', '', strtotime('-5 days'));
setcookie('password', '', strtotime('-5 days'));

header("location: http://localhost:8080/Project/index.php");
?>