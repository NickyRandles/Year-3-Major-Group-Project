<?php
include_once("include/header.php");

$sql = "UPDATE users SET messageCheck = now() WHERE username = '$log_username' LIMIT 1";
$query = mysql_query($sql);

$friends = array();

$sql = "SELECT user1 FROM conversations WHERE user2 = '$log_username'";
$query = mysql_query($sql);
while($row = mysql_fetch_array($query)){
	array_push($friends, $row["user1"])	;
}

$sql = "SELECT user2 FROM conversations WHERE user1 = '$log_username'";
$query = mysql_query($sql);
while($row = mysql_fetch_array($query)){
	array_push($friends, $row["user2"])	;
}

$conversationList = "";
foreach($friends as $friend){
	$sql = "SELECT id FROM messages WHERE sender = '$friend' AND reciever = '$log_username' AND sendTime > '$messageCheck' OR sender = '$log_username' AND reciever = '$friend' AND sendTime > '$messageCheck'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows > 0){
		$conversationList .= "<li><a href='#' onclick='getMessages(\"$friend\")'>($numRows) $friend</a></li>";
	}
	else{
		$conversationList .= "<li><a href='#' onclick='getMessages(\"$friend\")'>$friend</a></li>";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<style>
#conversation ul{
	padding: 5px;
	margin-top: 50px;
	text-align: center;
    list-style-type: none;
	background-color: #00BFFF;
	border: 2px solid blue;
	width: 20%;
	float: left;
	
}
#conversation li{
	border-top: 1px dotted white;
	padding: 10px;
	
}
#conversation a{
	color: white;
	text-decoration: none;
}
#conversation a:hover{
	text-decoration: underline;
}
#messages{
	margin-top: 50px;
	height: 400px;
	border: 2px solid blue;
	width: 75%;
	float: right;
}
#loader{
	padding: 2.5%;
	height: 30%;
	width: 30%;
	margin-left: 32.5%;
}
#messages h2{
	text-align: center;
	margin-top: 120px;
}

#allMessages{
	height: 320px;
	overflow: auto;
}
#messageBox{
	overflow: auto;
	padding: 5px;
}
#messageBox div{
	overflow: auto;
}
#messageBox div img{
	max-height: 60px;
	max-width: 10%;
	float: left;
}
#eachMessage{
	border-top: 1px solid blue;
	padding: 1%;
}
#time{
	float: left;
	width: 85%;
	text-align: center;
	margin-top: -0.5px;
}

#sent{
	padding-left: 1%;
	width: 80%;
	float: left;
	margin-top: -15px;
}
#controls{
	width: 90%;
	height: 80px;
	margin: 0 auto;
	background-color: #FAEBD7;
}
#controls textarea{
	width: 75%;
	height: 60px;
	margin-top: 1%;
	margin-left: 1%;
	float: left:
}

#controls button{
	margin-top: 25px;
	margin-right: 5%;
	padding: 5px;
	background: none;
	background-color: #8A2BE2;
	border: 1px solid white;
	color: white;
	width: 15%;
	float: right;
}

</style>
<script type="text/javascript">
var username = "";
$(document).ready(function(){	

	$.ajaxSetup({cache:false});
	setInterval(function(){
		if(username != ""){
			var myDiv = $("#allMessages");
			myDiv.animate({ scrollTop: myDiv.prop("scrollHeight") - myDiv.height() }, 1000);
			$("#messageBox").load("parsers/chatLogs.php?log=<?php echo $log_username ?>&user=" + username);
		}
	}, 1000);
	
});

function getMessages(user){	
	username = user;
	$("#messageBox").html("<img src='images/loader.gif' id='loader' alt='Loading...'>");
	$("#messageBox").load("parsers/chatLogs.php?log=<?php echo $log_username ?>&user=" + username);
}

function sendMessage(sender){
	var message = $("#message").val();
	if(message != ""){
		$.post("parsers/messageSystem.php", {sender: sender, reciever: username, message: message}, function(data){});
	}
	$("#message").val("");
}
</script>
</head>
<body>
<div id="conversation">
	<ul>
		<?php echo $conversationList ?>
	</ul>
</div>
<div id="messages">
<div id="allMessages">
	<div id="messageBox">
		<h2>No conversation selected</h2>
	</div>
</div>	
	<div id="controls">
		<textarea id="message"></textarea>
		<button onclick="sendMessage('<?php echo $log_username ?>')">Send</button>
	</div>
</div>
</body>
</html>