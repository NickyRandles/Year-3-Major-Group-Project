<?php 
include_once("include/checkLoginStatus.php");

if(isset($_GET["id"])){
	$id = $_GET["id"];
}
else{
	header("location: http://localhost:8080/Project");
	exit();
}

$members = "";
$posts = "";
$info = "";

//code for members
$sql = "SELECT * FROM groupmembers WHERE groupId = '$id' ORDER BY RAND() LIMIT 8";
$query = mysql_query($sql);

while($row = mysql_fetch_array($query)){
	$memberName = $row["memberName"];
	
	$sql2 = "SELECT * FROM users WHERE username = '$memberName' LIMIT 1";
	$query2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($query2);
	
	$image = $row2["profilePic"];
	
	$members .= "<div class='member' id='$memberName' onmouseover='showName(this.id)' onmouseout='hideName(this.id)'>";
	if($image != null){
		$members .= "<img src='$image' alt='$memberName'>";
	}
	else{
		$members .= "<img src='images/blankProfile.jpg' alt='$memberName'>";
	}
	$members .= "<a href='user.php?u=$memberName'>$memberName</a>";
	$members .= "</div>";
}

//code for posts
$sql = "SELECT * FROM groupposts WHERE groupId = '$id' ORDER by postTime DESC";
$query = mysql_query($sql);

while($row = mysql_fetch_array($query)){
	$postId = $row["id"];
	$poster = $row["poster"];
	$post = $row["comment"];
	$postTime = $row["postTime"];
	
	$posts .= "<div class='posts' id='post$postId'>";
	$posts .= "<h3><a href='user.php?u=$poster'>$poster</a></h3>";
	$posts .= "<span>$postTime</span>";
	$posts .= "<p>$post</p>";
	$posts .= "<div id='replies$postId'>";
	$sql2 = "SELECT * FROM groupreplies WHERE postId = '$postId'";
	$query2 = mysql_query($sql2);
	while($row2 = mysql_fetch_array($query2)){
		$replier = $row2["replier"];
		$reply = $row2["comment"];
		$replyTime = $row2["replyTime"];
		
		$posts .= "<div class='replies'>";
		$posts .= "<h3><a href='user.php?u=$replier'>$replier</a></h3>";
		$posts .= "<span>$replyTime</span>";
		$posts .= "<p>$reply</p>";
		$posts .= "</div>";
	}
	$posts .= "</div>";
	$posts .= "<input type='text' id='replyMessage' placeholder='Write a comment' onkeyup=\"writeReply('$postId', '$log_username')\">";
	$posts .= "</div>";
}



//code for info
$sql = "SELECT * FROM groups WHERE id = '$id' LIMIT 1";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
$groupName = $row["name"];
$description = $row["description"];
$image = $row["image"];
$creator = $row["creator"];
$dateCreated = $row["dateCreated"];

$info .= "<div id='pageDetails'>";
if($image != null){
	$info .= "<img src='$image' alt='$groupName'>";
}
else{
	$info .= "<img src='images/notAvailable.jpg' alt='$groupName'>";
}
$info .= "<h2>$groupName</h2>";
$info .= "<p>$description</p>";
$info .= "<p>Created by: <a href='user.php?u=$creator'>$creator</a>&nbsp;&nbsp;&nbsp;Created on: $dateCreated</p>";
$info .= "<div>";

$sql = "SELECT * FROM groupmembers WHERE groupId = '$id' AND memberName = '$log_username' AND memberType = 'm'";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
$isMod = false;
if($numRows > 0){
	$isMod = true;
}

$sql = "SELECT * FROM groupmembers WHERE groupId = '$id'";
$query = mysql_query($sql);
$isMember = false;

$info .= "<button class='groupButton' onclick='listMembers()'>List Members</button>";
$info .= "<table id='memberList' border='1'>";
while($row = mysql_fetch_array($query)){
	$memberName = $row["memberName"];
	$memberType = $row["memberType"];

	if($log_username == $memberName){
		$isMember = true;
	}
	
	$info .= "<tr>";
	if($log_username != $memberName){
		$info .= "<td>$memberName</td>";
		if($isMod == true){
			if($memberType == "m"){
				$info .= "<td id='mod$memberName'><button onclick=\"mod('unmod', '$memberName')\">Unmod</button></td>";
			}
			else if($memberType == "r"){
				$info .= "<td id='mod$memberName'><button onclick=\"mod('mod', '$memberName')\">Mod</button></td>";
			}
		}
	}
	$info .= "</tr>";
}
$info .= "</table>";

if($isMember == true){
	$info .= "<button class='groupButton' onclick='userOptions()'>User options</button>";
	$info .= "<div id='userOptions'>";
	$info .= "<label for='name'>Add users to group</label>";
	$info .= "<input type='text' name='name' id='name' autocomplete='off' onkeyup='suggestions()'>";
	$info .= "<div id='suggestions'></div>";
	$info .= "<div id='userAdded'></div>";
	$info .= "<button onclick=\"addMember('$id', '$groupName')\" id='add'>Add</button>";
	$info .= "<hr>";
	$info .= "<button onclick='leaveGroup()'>Leave group</button>";
	$info .= "</div>";
}

if($isMod == true){
	$info .= "<button class='groupButton' onclick='modOptions()'>Mod options</button>";
	$info .= "<div id='modOptions'>";
	$info .= "<button onclick='closeGroup()'>Close group</button>";
	$info .= "</div>";
}

$info .= "</div>";
?>
<?php include_once("include/header.php"); ?>
<!DOCTYPE html>
<html>
<head>
<head>
	<style>
		button{
			padding: 1%;
		}
		
		#members{
			width: 95%;
			height: 150px;
			margin: 0 auto;
			margin-top: 80px;
		}
		
		.member{
			width: 12.5%;
			height: 150px;
			float: left;
		}
		
		.member img{
			height: 150px;
			max-width: 100%;
			position: absolute;
			float: left;
		}
		
		.member a{
			position: relative;
			color: blue;
			font-size: 1.1em;
			text-decoration: none;
			display: none;
		}
		
		.member a:hover{
			text-decoration: underline;
		}
		
		#posts{
			border-top: 5px solid #00BFFF;
			border-left: 1px solid #00BFFF;
			border-right: 1px solid #00BFFF;
			background-color: #e9eaed;
			float: left;
			width: 65%;
			margin-top: 20px;
		}
		
		#posts #postTextArea{
			width: 70%;
			height: 70px;
			margin-left: 3%;
			margin-top: 2%;
			float: left;
		}
		
		#posts #postButton{
			width: 20%;
			float: left;
			margin: 0 auto;
			margin-left: 3%;
			margin-top: 4%;
			padding: 1%;
			background-color: #BA55D3;
			color: white;
			border: 3px solid white;
		}
		
		#enterPost{
			margin-bottom: 10px;
		}
		
		.posts{
			float: left;
			width: 80%;
			background-color: white;
			border: 1px solid #D3D3D3;
			padding: 2.5%;
			margin-left: 7.5%;
			margin-top: 20px;
		}
		
		.replies{
			margin-left: 5%;
		}
		
		#replyMessage{
			margin-left: 5%;
			height: 20px;
			width: 80%;
		}
		
		.groupButton{
			margin-top: 5px;
			width: 80%;
			padding: 2%;
			background-color: #00BFFF;
			color: white;
		}
		
		#pageDetails{
			text-align: center;
			margin-top: 20px;
			width: 30%;
			padding: 2%;
			border: 1px solid #00BFFF;
			float: right;
		}
		
		#pageDetails table{
			width: 80%;
			margin: 0 auto;
			border-collapse: collapse;
			border: 1px solid blue;
			padding: 5px;
		}
		
		#pageDetails td{
			padding: 1%;
		}
		
		#pageDetails img{
			width: 70%;
			height: 70%;
		}	
		
		#userOptions{
			width: 75%;
			margin: 0 auto;
			border: 1px solid blue;
			padding: 5px;
		}
		
		#name{
			width: 70%;
			height: 20px;
		}
		
		#add{
			margin-top: 5px;
			width: 25%;
		}
		
		#suggestions{
			position: absolute;
			width: 16%;
			padding: 5px;
			margin-top: -5px;
			margin-left: 2.5%;
		}

		#suggestions li{
			list-style-type: none;
			border: 1px solid blue;
			background-color: cyan;
			padding: 5px;
		}
		
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#tabs").tabs();
			$("#memberList").hide();
			$("#userOptions").hide();
			$("#modOptions").hide();
		});
		function showName(id){
			$("#" + id + " a").show();
		}
		function hideName(id){
			$("#" + id + " a").hide();
		}
		function addMember(id, name){
			var user = $("#name").val();
			
			if(name != ""){
				$.post("parsers/groupSystem.php", {groupId: id, groupName: name, addUser: user}, function(data){
					if(data == "addSuccess"){
						$("#userAdded").html(user + " added to group");
					}
					else if(data == "memberAlready"){
						$("#userAdded").html(user + " is already a member");
					}
					else if(data == "userDoestNoExist"){
						$("#userAdded").html(user + " does not exist");
					}
					else{
						alert(data);
					}
				});	
			}
			else{
				$("#userAdded").html("Please select a user first");
			}

		}

		function suggestions(){
			var name = $("#name").val();
			
			if(window.event.keyCode == 13){
				addMember();
			}	
			else if(name == ""){
				$("#suggestions").html("");
			}
			else{
				$.post("parsers/groupSystem.php", {name: name}, function(data){
					$("#suggestions").html(data);
					
					$("#suggestions li").click(function(){
						var pick = $(this).text();
						$("#name").val(pick);
						$("#suggestions").html("");
					});
				});
			}
		}
		function writePost(groupId, poster){
			var comment = $("#postTextArea").val();
			$.post("parsers/groupSystem.php", {groupId: groupId, poster: poster, comment: comment}, function(data){
				if(data != ""){
					$("#eachPost").prepend(data);
				}
				else{
					alert("Group chat down");
				}
			});
		}
		function writeReply(postId, replier){
			var message = $("#replyMessage").val();
			if(window.event.keyCode == 13){
				$.post("parsers/groupSystem.php", {postId: postId, replier: replier, message: message}, function(data){
					$("#replies" + postId).html(data);
					$("#replyMessage").val("");
				});
			}
		}
		
		function listMembers(){
			$("#memberList").toggle("blind");
		}
		
		function userOptions(){
			$("#userOptions").toggle("blind");
		}
		
		function mod(action, memberName){
			$.post("parsers/groupSystem.php", {action: action, groupId: '<?php echo $id; ?>', memberName: memberName}, function(data){
				if(data == "modded"){
					$("#mod" + memberName).html("<button onclick=\"mod('unmod', '" + memberName +"')\">Unmod</button>");
				}
				else if("unmodded"){
					$("#mod" + memberName).html("<button onclick=\"mod('mod', '" + memberName +"')\">Mod</button>");
				}
				else{
					alert(data);
				}
			});
		}
			
		function modOptions(groupId){
			$("#modOptions").toggle("blind");
		}
		
		function leaveGroup(){
			var conf = confirm("Are you sure you want to leave this group");
			if(conf != true){
				return false;
			}
			$.post("parsers/groupSystem.php", {memberName: '<?php echo $log_username?>', groupId: '<?php echo $id?>', modAction: "leave"}, function(data){
				if(data == "leaveSuccess"){
					window.location.assign("groups.php?u<?php echo $log_username; ?>");
				}
			});
		}
		
		function closeGroup(){
			var conf = confirm("Are you sure you want to close this group");
			if(conf != true){
				return false;
			}
			$.post("parsers/groupSystem.php", {memberName: '<?php echo $log_username?>', groupId: '<?php echo $id?>', modAction: "close"}, function(data){
				if(data == "closeSuccess"){
					window.location.assign("groups.php?u<?php echo $log_username; ?>");
				}
			});
		}
	</script>
</head>
<body>
<div id = "members">
	<?php echo $members; ?>
</div>
<div id = "posts">
	<div id = "enterPost">
		<textarea id="postTextArea" placeholder="Tell the group whats going on"></textarea>
		<button id="postButton" onclick="writePost('<?php echo $id ?>', '<?php echo $log_username?>')">Post</button>
	</div>
	<div id="eachPost">
		<?php echo $posts; ?>
	</div>
</div>
<div id = "info">
	<?php echo $info; ?>
</div>
</body>
</html>