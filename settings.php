<?php
error_reporting(E_ALL ^ E_WARNING);

include_once("include/checkLoginStatus.php");
$filePath = "";
if(isset($_FILES["profilePic"])){
		if(empty($_FILES["profilePic"]["name"])){
			echo "please choose a file";
		}
		else{
			$allowed = array("jpg", "jpeg", "png", "gif");
			$fileName = $_FILES["profilePic"]["name"];
			$fileExt =  strtolower(end(explode(".", $fileName)));
			$fileTemp = $_FILES["profilePic"]["tmp_name"];
			
			if(in_array($fileExt, $allowed)){
				$filePath = "users/$log_username/" . substr(md5(time()), 0, 10) . "." . $fileExt;
				move_uploaded_file($fileTemp, $filePath);
				$sql = "UPDATE users SET profilePic = '$filePath' WHERE username = '$log_username' LIMIT 1";
				$query = mysql_query($sql);
			}
			else{
				echo "Invalid valid type. File types allowed: " . implode(", ", $allowed);
			}
		}
}


?>
<?php include_once("include/header.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<style>
		#accordion{
			width: 40%;
			margin: 75px auto;
		}
		#accordion form{
			margin-left: 20%;
		}
		#accordion form img{
			width: 50%;
			margin-left: 15%;
			margin-bottom: 10px;
		}
		#accordion input[type="submit"]{
			margin-left: 25%;
		}
		#password label{
			display: block;
			float: left;
			width: 50%;
		}
		#password input[type="password"]{
			width: 48%;
			float: left;
		}
		#password button{
			width: 30%;
			margin-left: 60%;
			margin-top: 10px;
		}
		#close div{
			margin-top: 10px;
		}
		#close label{
			display: block;
			width: 35%;
			float: left;
		}
		#close input{
			width: 50%;
		}
		#close textarea{
			width: 50%;
		}
		#close button{
			width: 35%;
			margin-top: 10px;
			margin-left: 45%;
		}
		#error{
			color: red;
			font-size: 15px;
			text-align: center;
		}
	</style>
	<script>
		$(document).ready(function(){
			$("#accordion").accordion({heightStyle: 'panel'});
		});
		
		function changePassword(){
			var oldPassword = document.getElementById("oldPassword").value;
			var newPassword = document.getElementById("newPassword").value;
			var confirmPassword = document.getElementById("confirmPassword").value;
			
			$.post("parsers/settingSystem.php", {oldPassword: oldPassword, newPassword: newPassword, confirmPassword: confirmPassword}, function(data){
				$("#status").html(data);
			});
		}
		
		function closeAccount(){
			var password = document.getElementById("userPassword").value;
			var reason = document.getElementById("reason").value;
			$.post("parsers/settingSystem.php", {password: password, reason: reason}, function(data){
				if(data == "accountClosed"){
					window.location.assign("index.php");
				}
				else{
					$("#closeError").html(data);
				}
			});
		}
	</script>
</head>
<body>
<div id='accordion'>
	<h3>Change Profile Picture</h3>
	<div>
		<form action='#' method='post' enctype='multipart/form-data'>
		<?php
		if($filePath != null){
			echo "<img src='$filePath' alt='New profile pic'>";
		}
		?>
			<input type='file' name='profilePic'> <br><br> <input type='submit' value="Upload">
		</form>
	</div>
	<h3>Change Password</h3>
	<div id="password">
		<div id="status"></div>
		<label for="oldPassword">Enter password:</label>
		<input type="password" name="oldPassword" id="oldPassword"><br><br>
		<label for="newPassword">Enter new password:</label>
		<input type="password" name="newPassword" id="newPassword"><br><br>
		<label for="confirmPassword">Confirm new password:</label>
		<input type="password" name="confirmPassword" id="confirmPassword">
		<button onclick="changePassword()">Change</button>
	</div>
	<h3>Close Account</h3>
	<div id="close">
		<div id="closeError"></div>
		<div>
		<label for="password">Enter password:</label>
		<input type="password" name="password" id="userPassword">
		</div>
		<div>
		<label for="reason">Reason:</label>
		<textarea name="reason" id="reason"></textarea>
		</div>
		<button onclick="closeAccount()">Close Account</button>
	</div>
</div>
</body>
</html>