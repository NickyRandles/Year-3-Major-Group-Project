<?php
include_once("include/checkLoginStatus.php");

if(isset($_GET["u"])){
	$username = $_GET["u"];
}
else{
	header("location: http://localhost:8080/Project");
	exit();
}

$groups = "<h1>$username's groups</h1>";
if($log_username == $username){
	$groups = "<h1>Your groups</h1>";
}
$sql = "SELECT * FROM groupmembers WHERE memberName = '$username'";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
if($numRows < 1){
	$groups .= "<p>$username is not a member of any groups</p>";
}
else{
	
	while($row = mysql_fetch_array($query)){
		$groupId = $row["groupId"];
		
		$sql2 = "SELECT * FROM groups WHERE id = '$groupId'";
		$query2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($query2);
		
		$name = $row2["name"];
		$description = $row2["description"];
		$image = $row2["image"];
		
		$groups .= "<div class='group'>";
		if($image != null){
			$groups .= "<a href='group.php?id=$groupId'><img src='$image' alt='$name'></a>";
		}
		else{
			$groups .= "<a href='group.php?id=$groupId'><img src='images/notAvailable.jpg' alt='$name'></a>";
		}
		$groups .= "<a href='group.php?id=$groupId'><h2>$name</h2></a>";
		$groups .= "<p>$description</p>";
		$groups .= "</div>";

	}
	
}


?>
<?php include_once("include/header.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<style>
		#groups{
			margin-top: 35px;
			border: 2px solid #00BFFF;
			width: 70%;
			float: left;
		}
		
		#groups h1{
			text-align: center;
		}
		
		.group{
			border-top: 1px solid #7FFFD4;
			width: 90%;
			margin: 0 auto;
			clear: both;
		}
		
		.group img{
			width: 20%;
			float: left;
		}
		
		.group h2{
			float: left;
			margin-left: 10px;
			width: 70%;
		}
		
		.group p{
			float: left;
			width: 70%;
		}
		
		#userOptions{
			margin-top: 35px;
			border: 2px solid #00BFFF;
			padding: 2%;
			width: 25%;
			float: right;
		}
		
		#userOptions h2{
			text-align: center;
		}
		
		#userOptions div{
			margin-top: 10px;
		}
		
		#userOptions label{
			display: block;
			width: 40%;
			float: left;	
		}
		
		#groupName{
			width: 55%;
			height: 20px;
		}
		
		#groupDescription{
			width: 55%;
			height: 50px;
		}
		
		#create{
			background-color: #BA55D3;
			color: white;
			width: 30%;
			padding: 4px;
			margin-left: 50%;
		}

		#links a{
			text-align: center;
			text-decoration: none;
			color: black;
		}
		
		#links a:hover{
			text-decoration: underline;
		}
	</style>
	<script type="text/javascript">
		var count = 1;
		function createGroup(creator){
			var name = $("#groupName").val();
			var description = $("#groupDescription").val();
			
			$.post("parsers/groupSystem.php", {groupName: name, groupDesc: description, creator: creator}, function(data){
				if(data != "" && count <= 3){
					$("#links").append(data);
					$("#groupName").val("");
					$("#groupDescription").val("");
					count++;
				}			
			});
		}
	</script>
</head>
<body>
<div id="groups">
	<?php echo $groups; ?>
</div>
<div id="userOptions">
	<h2>Create new group</h2>
	<div>
		<label for="groupName">Group Name:</label>
		<input type="text" name="groupName" id="groupName" placeholder="Enter group name">
	</div>
	<div>
		<label for="groupDescription">Group Description:</label>
		<textarea name="groupDescription" id="groupDescription" placeholder="Enter Description"></textarea>
	</div>
	<button id="create" onclick="createGroup('<?php echo $log_username; ?>')">Create</button>
	<div id="links">
	</div>
</div>
</body>
</html>