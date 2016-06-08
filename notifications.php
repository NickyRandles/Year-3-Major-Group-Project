<?php
include_once("include/checkLoginStatus.php");
if($loggedIn != true){
	header("location: http://localhost:8080/Project/index.php");
}

$notifications = "";

$sql = "SELECT * FROM notifications WHERE username = '$log_username' ORDER BY dateMade DESC";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
if($numRows < 1){
	$notifications = "You have no notifications.";
}
else{
	while($rows = mysql_fetch_array($query)){
		$initiator = $rows["initiator"];
		$message = $rows["message"];
		$dateMade = $rows["dateMade"];
		
		$notifications .= "<p><a href='user.php?u=$initiator'>$initiator</a> - $message - $dateMade</p>";
	}
	
	$sql = "UPDATE users SET notificationCheck = now() WHERE username = '$log_username' LIMIT 1";
	$query = mysql_query($sql);
}

$friendRequests = "";

$sql = "SELECT * FROM friends WHERE asked = '$log_username' AND accepted = 'n' ORDER BY dateMade ASC";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
if($numRows < 1){
	$friendRequests = "You have now friend requests";
}
else{
	while($rows = mysql_fetch_array($query)){
		$requestId = $rows["id"];
		$asker = $rows["asker"];
		$dateMade = $rows["dateMade"];
		
		$friendRequests .= "<div class='requests'></div>";
		$friendRequests .= "<p><a href='user.php?u=$asker'>$asker</a></p>";
		$friendRequests .= "<div class='options' id='options_$requestId'>";
		$friendRequests .= "<button onclick=\"friendRequestHandler('accept', '$requestId', '$asker', 'options_$requestId')\">Accept</button>";
		$friendRequests .= "<button onclick=\"friendRequestHandler('decline', '$requestId', '$asker', 'options_$requestId')\">Decline</button>";
		$friendRequests .= "</div>";
		$friendRequests .= "<p><a href='user.php?u=$asker'>$asker</a> requested on: $dateMade</p>";
		$friendRequests .= "</div>";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="js/ajax.js" type="text/javascript"></script>
	<script type="text/javascript">
		function friendRequestHandler(action, requestId, asker, elem){
			var conf = confirm("Are you sure you want to " + action + "'s " + asker + " request?");
			if(conf != true){
				return false;
			}
			var element = document.getElementById(elem);
			element.innerHTML = "Please wait...";
			var ajax = ajaxObj("POST", "parsers/friendSystem.php");
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax) == true){
					if(ajax.responseText == "acceptSuccess"){
						element.innerHTML = "Request Accept!";
					}
					else if(ajax.responseText == "declineSuccess"){
						element.innerHTML = "Request Declined!";
					}
					else{
						element.innerHTML = ajax.responseText;
					}
				}
			}
			ajax.send("action=" + action + "&requestId=" + requestId + "&asker=" + asker);
		}

	</script>
</head>
<body>
<?php include_once("include/header.php")?>
<div id="notificationBox">
	<h2>Notifications</h2>
	<?php echo $notifications ?>
</div>
<div id="friendBox">
	<h2>Friend Requests</h2>
	<?php echo $friendRequests ?>
</div>
</body>
</html>