<?php
$adminOptions = "";
if($admin == true){
	$adminOptions = "<span id='break'> | </span><a href='approvePosts.php'>Approve posts</a><span id='break'> | </span>";
	$adminOptions .= "<a href='reports.php'>Reports</a>";			
}

$profilePic = "";
$sql = "SELECT profilePic, messageCheck FROM users WHERE username = '$log_username' LIMIT 1";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
$pic = $row["profilePic"];
$messageCheck = $row["messageCheck"];

if(!empty($pic)){
	$profilePic = "<img src='$pic' alt='Profile'>";
}
else{
	$profilePic = "<img src='images/blankProfile.jpg' alt='Profile'>";
}

$notifications = "<h3 id='noteHead'>Notifications</h3>";
$requests = "<h3 id='friendHead'>Friend Requests</h3>";

$sql = "SELECT * FROM notifications WHERE username = '$log_username' AND seen = 'n'";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
$notePic = "";
if($numRows < 1){
	$notePic = "images/notifications/0.png";
}
else if($numRows == 1){
	$notePic = "images/notifications/01.png";
}
else if($numRows == 2){
	$notePic = "images/notifications/02.png";
}
else if($numRows == 3){
	$notePic = "images/notifications/03.png";
}
else if($numRows == 4){
	$notePic = "images/notifications/04.png";
}
else if($numRows == 5){
	$notePic = "images/notifications/05.png";
}
else if($numRows > 5){
	$notePic = "images/notifications/05+.png";
}

$sql = "SELECT * FROM notifications WHERE username = '$log_username' ORDER by dateMade DESC";
$query = mysql_query($sql);
while($row = mysql_fetch_array($query)){
	$id = $row["id"];
	$initiator = $row["initiator"];
	$mess = $row["message"];
	$link = urldecode($row["link"]);
	$dateMade = $row["dateMade"];
	$seen = $row["seen"];
	$sql2 = "SELECT * FROM users WHERE username = '$initiator' LIMIT 1";
	$query2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($query2);
	$image = $row2["profilePic"];
	
	$notifications .= "<a href='$link' onmouseover=\"noteSeen('$id', '$initiator', '$dateMade')\">";
	if($seen == "n"){
		$notifications .= "<div class='notRead' id='$id'>";
	}
	else{
		$notifications .= "<div id='$id'>";
	}
	if($image != null){
		$notifications .= "<img src='$image' alt='$initiator'>";
	}
	else{
		$notifications .= "<img src='images/blankProfile.jpg' alt='$initiator'>";
	}
	$notifications .= "<p id='dateMade'>" . date('F j, Y, g:i a', strtotime($dateMade)) . "</p>";
	$notifications .= "<p>";
	$notifications .= "<b>$initiator</b> - $mess";
	$notifications .= "</p>";
	$notifications .= "</div>";
	$notifications .= "</a>";
}

$requestPic = "";
$sql = "SELECT * FROM friends WHERE asked = '$log_username' AND accepted = 'n' ORDER BY dateMade ASC";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
if($numRows < 1){
	$requestPic = "images/request.png";
	$requests .= "<p id='noRequests'>You have now friend requests</p>";
}
else{
	$requestPic = "images/newRequest.png";
	while($row = mysql_fetch_array($query)){
		$requestId = $row["id"];
		$asker = $row["asker"];
		$dateMade = $row["dateMade"];
		
		$sql2 = "SELECT profilePic FROM users WHERE username = '$asker' LIMIT 1";
		$query2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($query2);
		$image = $row2["profilePic"];
		
		$requests .= "<div id='eachRequest'>";
		if($image != null){
			$requests .= "<img src='$image'>";
		}
		else{
			$requests .= "<img src='images/blankProfile.jpg'>";
		}
		$time = date("F j, Y", strtotime($dateMade));
		$requests .= "<p><a href='user.php?u=$asker'>$asker</a></p>";
		$requests .= "<p>Requested on: $time</p>";
		$requests .= "<div class='options' id='options_$requestId'>";
		$requests .= "<button onclick=\"friendRequestHandler('accept', '$requestId', '$asker', 'options_$requestId')\">Accept</button>";
		$requests .= "<button onclick=\"friendRequestHandler('decline', '$requestId', '$asker', 'options_$requestId')\">Decline</button>";
		$requests .= "</div>";
		$requests .= "</div>";
	}
}

$sql = "SELECT id FROM messages WHERE sender = '$log_username'  AND sendTime > '$messageCheck' OR reciever = '$log_username' AND sendTime > '$messageCheck'";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
$messageImg = "";
if($numRows > 0){
	$messageImg = "images/newMail.png";
}
else{
	$messageImg = "images/mail.png";
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="css/header.css" type="text/css" rel="stylesheet">
	<script src="js/ajax.js" type="text/javascript"></script>
	<style>	
		#home{
			width: 150px;
			height: 12%;
			float: left;
		}
		#curve{
			width: 2%;
			height: 72px;
			background: url("images/curve.png") no-repeat;
			float: left;
			margin-top: 3%;
		}
		#bar{
			width: 84%;
			height: 72px;
			background: url("images/bar.png") repeat-x;
			float: left;
			margin-top: 3%;
			margin-bottom: 10px;
		}
		#options{
			margin-top: 25px;
		}
		#options a{
			padding: 10px;
			text-decoration: none;
			color: white;
		}
		#options span{
			color: white;
		}
		#leftOptions{
			float: left;
			margin-left: 20px;
		}
		#rightOptions{
			float: right;
			margin-right: 20px;
		}
		#rightInnerLeft{
			float: left;
		}
		#rightInnerRight{
			float: left;
		}
		#rightInnerLeft img{
			height: 40px;
			width: 40px;
			margin-top: -10px;
		}
		#rightInnerRight img{
			height: 40px;
			width: 40px;
			margin-top: -10px;
			margin-left: 10px;
		}	
		#rightInnerRight span{
			margin-left: -65px;
		}
		#notifications{
			position: absolute;
			height: 500px;
			width: 25%;
			border: 2px solid #477ee1;
			background-color: white;
			margin-top: 115px;
			margin-left: 62.5%;
			z-index: 4;
			display: none;
			overflow: auto;
		}
		
		#notifications div{
			padding: 1%;
			border: 1px solid blue;
			clear: both;
			overflow: auto;
		}
		
		.notRead{
			background-color: cyan;
		}
		
		#notifications a{
			text-decoration: none;
			color: black;
		}
		
		#notifications img{
			width: 20%;
			float: left;
		}
		
		#notifications p{
			padding: 0;
			margin: 0;
			margin-left: 1%;
			float: left;
			width: 75%;
		}
		
		#dateMade{
			text-align: center;
			font-size: 12px;
		}
		
		#notArrow{
			display: none;
			content: '';
			position: absolute;
			width: 0;
			height: 0;
			border-top: 10px solid transparent;
			border-bottom: 20px solid #477ee1;
			border-left: 10px solid transparent;
			border-right: 10px solid transparent;
			margin-top: 85px;
			margin-left: 73.8%;
		}
		#friendRequests{
			position: absolute;
			height: 500px;
			width: 25%;
			border: 2px solid #477ee1;
			background-color: white;
			margin-top: 115px;
			margin-left: 66%;
			z-index: 4;
			display: none;
			overflow: auto;
		}
		
		#eachRequest{
			margin: 1%;
			padding: 1%;
			border: 2px solid blue;
			clear: both;
			overflow: auto;
		}
		
		#friendRequests img{
			width: 20%;
			float: left;
		}
		
		#friendRequests p{
			padding: 0;
			margin: 0;
			margin-left: 1%;
			float: left;
			width: 75%;
			margin-bottom: 5px;
		}
		
		.options{
			margin-left: 20%;
		}
		
		.options button{
			width: 30%;
			padding: 1%;
			margin-left: 5%;
		}
		
		#friendsArrow{
			display: none;
			content: '';
			position: absolute;
			width: 0;
			height: 0;
			border-top: 10px solid transparent;
			border-bottom: 20px solid #477ee1;
			border-left: 10px solid transparent;
			border-right: 10px solid transparent;
			margin-top: 85px;
			margin-left: 77.3%;
		}
		
		#notButton, #friendB{
			cursor: pointer;
		}
		
		#noteHead, #friendHead{
			color: #00BFFF;
			text-align: center;
		}
		
		#noRequests{
			text-align: center;
		}

	</style>
	<script src="jquery/jquery-1.11.2.min.js"></script>
	<script src="jquery/jquery-ui.min.js"></script>
	<link href="jquery/jquery-ui.min.css" type="text/css" rel="stylesheet">
	<script>
		$(document).ready(function(){
			$("#notButton").click(function(){
				$("#friendsArrow").hide("blind");
				$("#friendRequests").hide("blind");
				$("#notArrow").toggle("blind");
				$("#notifications").toggle("blind");
			});
			$("#friendB").click(function(){
				$("#notArrow").hide("blind");
				$("#notifications").hide("blind");
				$("#friendsArrow").toggle("blind");
				$("#friendRequests").toggle("blind");
			})

		});
		
		function getSearchItem(value){
			$.post("getSearchItem.php", {searchItem:value}, function(data){
				$("#searchSuggestions").html(data);
					
				$("#searchSuggestions li").click(function(){
					var result = $(this).text();
					$("#search").val(result);
					$("#searchSuggestions").html('');
				});
			});
		}
		function noteSeen(element, initiator, dateMade){
			$.post("parsers/notificationSystem.php", {initiator: initiator, dateMade: dateMade}, function(data){
				if(data == "updated"){
					$("#" + element).css("background-color", "white");
				}
				else{
					alert("Try again later");
					alert(data);
				}
			});
		}
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
<header>

<div id="notArrow"></div><div id="notifications"><?php echo $notifications; ?></div>
<div id="friendsArrow"></div><div id="friendRequests"><?php echo $requests; ?></div>
	<a href="index.php"><img src="images/header_icon.jpg" alt="Shop and Save" id="home" /></a>
	<div id="curve"></div>
	<div id="bar">
		<div id="options">
			<div id="leftOptions">
				<a href="posts.php?u=<?php echo $log_username ?>">My Posts</a><span id="break"> | </span>
				<a href="groups.php?u=<?php echo $log_username ?>">My Groups</a><span id="break"> | </span>
				<a href="friends.php?u=<?php echo $log_username ?>">My Friends</a><span id="break"> | </span>
				<a href="createPost.php">Create Post</a>
				<?php echo $adminOptions ?>
			</div>
			<div id="rightOptions">
				<?php
					include_once("include/db_connect.php");
					$sql = "SELECT username FROM users WHERE username = '$log_username' limit 1";
					$query = mysql_query($sql);
					$row = mysql_fetch_array($query);
					$user = $row["username"];
				?>
				<div id="rightInnerLeft">
					<?php
						echo "<a href='user.php?u=$user'>$profilePic $user</a><span id='break'> | &nbsp; </span>";
					?>
				</div>
				<div id="rightInnerRight">
					<a id="notButton"><img src="<?php echo $notePic; ?>" alt="Notifications">&nbsp;</a>
					<a id="friendB"><img src="<?php echo $requestPic; ?>" alt="Friend request"></a>
					<a href="conversations.php"><img src="<?php echo $messageImg; ?>" alt="Messages"></a>
					<a href="settings.php"><img src="images/setting.png" alt="Settings" id="settings"></a>
					<span id="break"> | </span><a href="logout.php">Logout</a>
				</div>
			</div>
		</div>

	</div>
	<?php include_once("headerBasics.php"); ?>
</header>
<script>
	$("#home").mouseover(function() { 
		$(this).attr("src", "images/homepage_icon.jpg");
	});
	$("#home").mouseout(function() {
		$(this).attr("src", "images/header_icon.jpg");
	});
</script>
</body>
</html>