<?php
include_once("../include/db_connect.php");

if(isset($_POST["id"]) && isset($_POST["input"])){
	$id = $_POST["id"]; 
	$input = $_POST["input"];
	
	$reg1 = "/^[a-zA-Z0-9]+$/";
	$reg2 = "/^[a-zA-Z]+$/";
	$reg3 = "/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/";
	
	if($id == "username"){
		$sql = "SELECT username FROM users WHERE username = '$input' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		
		if($numRows > 0){
			echo "usernameTaken";
			exit();
		}
		else if(strlen($input) < 4 || strlen($input) > 10){
			echo "usernameWrongLength";
			exit();
		}
		else if(preg_match($reg1, $input)){
			echo "usernameOk";
			exit();
		}
		else if(!preg_match($reg1, $input)){
			echo "usernameInvalid";
			exit();
		}	
	}
	else if($id == "firstName"){
		if(preg_match($reg2, $input)){
			echo "firstNameOk";
		}
		else{
			echo "firstNameInvalid";
		}		
	}
	else if($id == "lastName"){
		if(preg_match($reg2, $input)){
			echo "lastNameOk";
		}
		else{
			echo "lastNameInvalid";
		}		
	}
	else if($id == "email"){
		if(preg_match($reg3, $input)){
			echo "emailOk";
		}
		else{
			echo "emailInvalid";
		}			
	}
}

if(isset($_POST["id"]) && isset($_POST["password"]) && isset($_POST["input"])){
	$id = $_POST["id"];
	$password = $_POST["password"];
	$input = $_POST["input"];

	if(!empty($input)){
		if($id == "confirmPassword"){
		if($input == $password){
			echo "passwordOk";
		}
		else{
			echo "passwordInvalid";
		}
	}
	}
	
}


?>