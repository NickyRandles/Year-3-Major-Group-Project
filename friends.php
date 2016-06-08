<?php
include_once("include/header.php");
if($loggedIn != true){
	header("location: http://localhost:8080/Project/");
	exit();
}
if(isset($_GET["u"])){
	$username = $_GET["u"];
}
else{
	header("location: http://localhost:8080/Project/");
	exit();
}
$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
if($numRows  < 1){
	header("location: http://localhost:8080/Project/");
	exit();
}
else{
	$row = mysql_fetch_array($query);
	$userCity = $row["city"];
	$userCounty = $row["county"];
}
$allFriends = "";
$sameCityFriends = "No friends from same City";
$sameCountyFriends = "No friends from same County";

$sql = "SELECT id FROM friends WHERE asker = '$username' AND accepted = 'y' OR asked = '$username' AND accepted = 'y'";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);

if($numRows < 1){
	if($log_username == $username){
		$allFriends = "You have no friends.";
	}
	else{
		$allFriends = "$username has no friends.";
	}
}
else{
	//code for all friends
	$sql = "SELECT * FROM friends WHERE asked = '$username' AND accepted = 'y'";
	$query1 = mysql_query($sql);
	
	$sql = "SELECT * FROM friends WHERE asker = '$username' AND accepted = 'y'";
	$query2 = mysql_query($sql);
	
	$friends = array();
	
	while($row1 = mysql_fetch_array($query1)){
		array_push($friends, $row1["asker"]);
	}
	while($row2 = mysql_fetch_array($query2)){
		array_push($friends, $row2["asked"]);
	}

	$allFriends = "<h2>All friends</h2>";
	$allFriends .= "<table id='allFriendTable'>";
	$i = 0;
	foreach($friends as $friend){
		$sql = "SELECT * FROM users WHERE username = '$friend' LIMIT 1";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		
		$firstName = $row["firstName"];
		$lastName = $row["lastName"];
		$image = $row["profilePic"];
		
		if($i % 3 == 0){
			$allFriends .= "<tr>";
			$allFriends .= "<td id='$friend'>";
			if($image != null){
				$allFriends .= "<a href='user.php?u=$friend'><img src='$image' alt='$friend'></a>";
			}
			else{
				$allFriends .= "<a href='user.php?u=$friend'><img src='images/blankProfile.jpg' alt='$friend'></a>";
			}
			$allFriends .= "<p><a href='user.php?u=$friend'>$friend</a></p>";
			$allFriends .= "<p>$firstName $lastName</p>";
			$allFriends .= "<span class='unfriend'><button class='unfriendButton' onclick=\"unfriend('$friend')\"></button>Unfriend</span>";
			$allFriends .= "</td>";
		}
		else{
			$allFriends .= "<td id='$friend'>";
			if($image != null){
				$allFriends .= "<a href='user.php?u=$friend'><img src='$image' alt='$friend'></a>";
			}
			else{
				$allFriends .= "<a href='user.php?u=$friend'><img src='images/blankProfile.jpg' alt='$friend'></a>";
			}
			$allFriends .= "<p><a href='user.php?u=$friend'>$friend</a></p>";
			$allFriends .= "<p>$firstName $lastName</p>";
			$allFriends .= "<span class='unfriend'><button class='unfriendButton' onclick=\"unfriend('$friend')\"></button>Unfriend</span>";
			$allFriends .= "</td>";
		}

		$i++;
	}
	$allFriends .= "</tr></table>";

	//code for friends in same city
	$sql = "SELECT * FROM friends WHERE asked = '$username' AND accepted = 'y'";
	$query1 = mysql_query($sql);
	
	$sql = "SELECT * FROM friends WHERE asker = '$username' AND accepted = 'y'";
	$query2 = mysql_query($sql);
	
	$friends = array();
	
	while($row1 = mysql_fetch_array($query1)){
		array_push($friends, $row1["asker"]);
	}
	while($row2 = mysql_fetch_array($query2)){
		array_push($friends, $row2["asked"]);
	}
	
	$sameCityFriends = "<h2>Friends from same City</h2>";
	$sameCityFriends .= "<table id='sameCityTable'>";
	$i = 0;
	foreach($friends as $friend){
		$sql = "SELECT * FROM users WHERE username = '$friend' AND city = '$userCity' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows > 0){
			$row = mysql_fetch_array($query);

			$firstName = $row["firstName"];
			$lastName = $row["lastName"];
			$image = $row["profilePic"];
			
			if($i % 3 == 0){
				$sameCityFriends .= "<tr>";
				$sameCityFriends .= "<td id='$friend'>";
				if($image != null){
					$sameCityFriends .= "<a href='user.php?u=$friend'><img src='$image' alt='$friend'></a>";
				}
				else{
					$sameCityFriends .= "<a href='user.php?u=$friend'><img src='images/blankProfile.jpg' alt='$friend'></a>";
				}
				$sameCityFriends .= "<p><a href='user.php?u=$friend'>$friend</a></p>";
				$sameCityFriends .= "<p>$firstName $lastName</p>";
				$sameCityFriends .= "<span class='unfriend'><button class='unfriendButton' onclick=\"unfriend('$friend')\"></button>Unfriend</span>";
				$sameCityFriends .= "</td>";
			}
			else{
				$sameCityFriends .= "<td id='$friend'>";
				if($image != null){
					$sameCityFriends .= "<a href='user.php?u=$friend'><img src='$image' alt='$friend'></a>";
				}
				else{
					$sameCityFriends .= "<a href='user.php?u=$friend'><img src='images/blankProfile.jpg' alt='$friend'></a>";
				}
				$sameCityFriends .= "<p><a href='user.php?u=$friend'>$friend</a></p>";
				$sameCityFriends .= "<p>$firstName $lastName</p>";
				$sameCityFriends .= "<span class='unfriend'><button class='unfriendButton' onclick=\"unfriend('$friend')\"></button>Unfriend</span>";
				$sameCityFriends .= "</td>";
			}
			$i++;
		}		
	}
	$sameCityFriends .= "</tr></table>";
	
	//code for friends in same county
	$sql = "SELECT * FROM friends WHERE asked = '$username' AND accepted = 'y'";
	$query1 = mysql_query($sql);
	
	$sql = "SELECT * FROM friends WHERE asker = '$username' AND accepted = 'y'";
	$query2 = mysql_query($sql);
	
	$friends = array();
	
	while($row1 = mysql_fetch_array($query1)){
		array_push($friends, $row1["asker"]);
	}
	while($row2 = mysql_fetch_array($query2)){
		array_push($friends, $row2["asked"]);
	}
	
	$sameCountyFriends = "<h2>Friend from same County</h2>";
	$sameCountyFriends .= "<table id='sameCountyTable'>";
	$i = 0;
	foreach($friends as $friend){
		$sql = "SELECT * FROM users WHERE username = '$friend' AND county = '$userCounty' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows > 0){
			$row = mysql_fetch_array($query);

			$firstName = $row["firstName"];
			$lastName = $row["lastName"];
			$image = $row["profilePic"];

			if($i % 3 == 0){
				$sameCountyFriends .= "<tr>";
				$sameCountyFriends .= "<td id='$friend'>";
				if($image != null){
					$sameCountyFriends .= "<a href='user.php?u=$friend'><img src='$image' alt='$friend'></a>";
				}
				else{
					$sameCountyFriends .= "<a href='user.php?u=$friend'><img src='images/blankProfile.jpg' alt='$friend'></a>";
				}
				$sameCountyFriends .= "<p><a href='user.php?u=$friend'>$friend</a></p>";
				$sameCountyFriends .= "<p>$firstName $lastName</p>";
				$sameCountyFriends .= "<span class='unfriend'><button class='unfriendButton' onclick=\"unfriend('$friend')\"></button>Unfriend</span>";
				$sameCountyFriends .= "</td>";
			}
			else{
				$sameCountyFriends .= "<td id='$friend'>";
				if($image != null){
					$sameCountyFriends .= "<a href='user.php?u=$friend'><img src='$image' alt='$friend'></a>";
				}
				else{
					$sameCountyFriends .= "<a href='user.php?u=$friend'><img src='images/blankProfile.jpg' alt='$friend'></a>";
				}
				$sameCountyFriends .= "<p><a href='user.php?u=$friend'>$friend</a></p>";
				$sameCountyFriends .= "<p>$firstName $lastName</p>";
				$sameCountyFriends .= "<span class='unfriend'><button class='unfriendButton' onclick=\"unfriend('$friend')\"></button>Unfriend</span>";
				$sameCountyFriends .= "</td>";
			}
			$i++;
		}		
	}
	$sameCountyFriends .= "</tr></table>";
}
?>
<!DOCTYPE html>
<html>
<head>
	<script src="jquery/jquery-1.11.2.min.js"></script>
	<script src="jquery/jquery-ui.min.js"></script>
	<link href="jquery/jquery-ui.min.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="js/nav_bar.js"></script>
	
	<script type="text/javascript">
		$("document").ready(function(){
			$("#tabs").tabs();
		});
		function unfriend(friend){
			var unfriend = document.getElementById("unfriend");
			
			$.post("parsers/friend.php", {friend: friend}, function(data){
				if(data == "unfriendSuccessful"){
					$("#" + friend).html("You have removed " + friend);
					setTimeout(function(){
						$("#" + friend).fadeOut();
					}, 1500);
					
				}
				else if(data == "friendshipEndedAlready"){
					$("#" + friend).html("Your friendship with " + friend + " has already ended");
				}
				else{
					alert(data);
				}
			});
		}
	
	</script>
	<style>
		h1{
			text-align: center;
			margin-top: 80px;
			font-family: Arial, Helvetica, sans-serif;
			color: #00BFFF;
		}
		
		#tabs{
			overflow: auto;
			width: 90%;
			margin: 0 auto;
		}
		
		h2{
			text-align: center;
		}
		
		table{
			width: 100%;
			margin: 0 auto;
			border-spacing: 25px;
		}

		table img{
			height: 150px;
			max-width: 90%;
		}
		td{
			border: 2px solid #00BFFF;
			padding: 1%;
			text-align: center;
			margin-right: 30px;
			width: 30%;
		}
		
		.unfriendButton{
			border: none;
			height: 16px;
			width: 16px;
			background: url("images/x.png") no-repeat;
		}
		
		.unfriend{
			font-size: 16px;
		}
		
		input{
			float: right;
			margin-right: 2%;
			padding: 5px;
		}
		
	</style>
</head>
<body>
<?php 
if($log_username == $username){
	echo "<h1>Your friends</h1>"; 
}
else{
	echo "<h1>$username friends</h1>"; 
}
?> 
<div id="tabs">
	<ul>
		<li><a href="#tab1">All</a></li>
		<li><a href="#tab2">Same City</a></li>
		<li><a href="#tab3">Same County</a></li>
	</ul>
	<div id="tab1">
		<?php echo $allFriends ?>
	</div>
	<div id="tab2">
		<?php echo $sameCityFriends ?>
	</div>
	<div id="tab3">
		<?php echo $sameCountyFriends ?>
	</div>
</div>
</body>
</html>