<?php
include_once("include/header.php");
include_once("include/checkLoginStatus.php");

$username = "";
$email = "";
$gender = "";
$city = "";
$county = "";
$lastOnline = "";
$dateJoined = "";
$userType = "";

if(isset($_GET["u"])){
	$username = $_GET["u"];
}
else{
	header("location: http://localhost:8080/Project/index.php");
	exit();
}

$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);

if($numRows < 1){
	header("location: http://localhost:8080/Project/index.php");
	exit();
}

else{
	while($row = mysql_fetch_array($query)){
		$username = $row["username"];
		$email = $row["email"];
		$city = $row["city"];
		$county = $row["county"];
		$lastOnline = $row["lastLogin"];
		$dateJoined = $row["signUp"];
		$pic = $row["profilePic"];
		$userType = $row["userType"];
		
		if(!empty($pic)){
			$profilePic = "<img src = '$pic' alt='$username profile picture'>";
		}
		else{
			$profilePic = "<img src = 'images/blankProfile.jpg' alt='$username hasn't uploaded a profile pic yet'>";
		}
		if($row["gender"] == "m"){
			$gender = "Male";
		}
		else{
			$gender = "Female";
		}
	}
}

$accountOwner = "no";
if($username == $log_username){
	$accountOwner = "yes";
}

?>
<?php
	$isFriend = false;
	$loggedBlockedUser = false;
	$userBlockedLogged = false;
	$loggedIsAdmin = false;
	
	if($username != $log_username && $loggedIn == true){
		$sql = "SELECT id FROM friends WHERE asker = '$log_username' AND asked = '$username' AND accepted = 'y'
		or asker = '$username' AND asked = '$log_username' AND accepted = 'y' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows > 0){
			$isFriend = true;
		}
		
		$sql2 = "SELECT id FROM blockedusers WHERE blocker = '$log_username' and blockee = '$username' LIMIT 1";
		$query2 = mysql_query($sql2);
		$numRows2 = mysql_num_rows($query2);
		if($numRows2 > 0){
			$loggedBlockedUser = true; 
		}
		
		$sql3 = "SELECT id FROM blockedusers WHERE blocker = '$username' and blockee = '$log_username' LIMIT 1";
		$query3 = mysql_query($sql3);
		$numRows3 = mysql_num_rows($query3);
		if($numRows3 > 0){
			$userBlockedLogged = true; 
		}
		
		$sql4 = "SELECT id FROM users WHERE username = '$log_username' AND userType = 'a' LIMIT 1";
		$query4 = mysql_query($sql4);
		$numRows4 = mysql_num_rows($query4);
		if($numRows4 > 0){
			$loggedIsAdmin = true;
		}
	}
	
	$friendButton = "";
	$blockedButton = "";
	$banButton = "";
	
	if($isFriend == true){
		$friendButton = "<button onclick=\"friendToggle('unfriend' ,'$username', 'friendButton')\">Unfriend</button>";
	}
	else if($loggedIn == true && $log_username != $username && $userBlockedLogged == false){
		$friendButton = "<button onclick=\"friendToggle('friend' ,'$username', 'friendButton')\">Request As Friend</button>";
	}
	
	if($loggedBlockedUser == true){
		$blockedButton = "<button onclick=\"blockToggle('unblock' ,'$username', 'blockButton')\">Unblock</button>";
	}
	else if($loggedIn == true && $log_username != $username){
		$blockedButton = "<button onclick=\"blockToggle('block' ,'$username', 'blockButton')\">Block</button>";
	}
	
	if($loggedIsAdmin == true){
		$sql = "SELECT id FROM users WHERE username = '$username' and userType = 'b' LIMIT 1";
		$query = mysql_query($sql);
		$numRows = mysql_num_rows($query);
		if($numRows > 0){
			$banButton = "<button onclick=\"banToggle('unban', '$username')\">Unban</button>";
		}
		else{
			$banButton = "<button onclick=\"banToggle('ban', '$username')\">Ban</button>";
		}
	}
	
?>
<?php 
//code for user activity div
$sql = "SELECT id FROM items WHERE poster = '$username'";
$query = mysql_query($sql);
$allPostsNo = mysql_num_rows($query);

$sql = "SELECT id FROM items WHERE poster = '$username' and status = 'a'";
$query = mysql_query($sql);
$activePostsNo = mysql_num_rows($query);

$sql = "SELECT id FROM items WHERE poster = '$username' and status = 'p'";
$query = mysql_query($sql);
$pendingPostsNo= mysql_num_rows($query);

$sql = "SELECT id FROM items WHERE poster = '$username' and status = 'w'";
$query = mysql_query($sql);
$withdrawnPostsNo = mysql_num_rows($query);

//code for friends div
$friendDiv = "<h1>Friends</h1>";
$sql = "SELECT asked FROM friends WHERE asker = '$username' AND accepted = 'y'";
$query1 = mysql_query($sql);

$sql = "SELECT asker FROM friends WHERE asked = '$username' AND accepted = 'y'";
$query2 = mysql_query($sql);

$friends = array();

while($rows1 = mysql_fetch_array($query1)){
	array_push($friends, $rows1["asked"]);
}
while($rows2 = mysql_fetch_array($query2)){
	array_push($friends, $rows2["asker"]);
}

if(!empty($friends)){
	$i = 0;
	foreach($friends as $friend){
		if($i < 6){
			$sql = "SELECT username, profilePic from users WHERE username = '$friend' LIMIT 1";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$picture = $row["profilePic"];
			if(!empty($picture)){
				$friendDiv .= "<a href='user.php?u=$friend'><img src='$picture' title='$friend'></a>";
			}
			else{
				$friendDiv .= "<a href='user.php?u=$friend'><img src='images/blankProfile.jpg' title='$friend'></a>";
			}
			
		}
		$i++;
	}
	$friendDiv .= "<p><a href='friends.php?u=$username'>View all</a></p>";
}
else{
	if($accountOwner == "yes"){
		$friendDiv .= "<p>You have no friends</p>";
		$friendDiv .= "<p><a href='search.php'>Search for people</a></p>";
	}
	else{
		$friendDiv .= "<p>$username has no friends</p>";
	}
	
}
//code for groups
$groups = "<h1>Groups</h1>";

$sql = "SELECT * FROM groupmembers WHERE memberName = '$username' LIMIT 4";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);
if($numRows > 0){
	while($row = mysql_fetch_array($query)){
		$groupId = $row["groupId"];
		
		$sql2 = "SELECT * FROM groups WHERE id = '$groupId' LIMIT 1";
		$query2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($query2);
		
		$name = $row2["name"];
		$image = $row2["image"];
		
		if($image != null){
			$groups .= "<div>";	
			$groups .= "<a href='group.php?id=$groupId'><img src='$image'><a>";	
			$groups .= "<br><a href='group.php?id=$groupId'><img src='$image'>$name<a>";
			$groups .= "</div>";				
		}
		else{
			$groups .= "<div>";	
			$groups .= "<a href='group.php?id=$groupId'><img src='images/notAvailable.jpg'><a>";	
			$groups .= "<br><a href='group.php?id=$groupId'>$name<a>";
			$groups .= "</div>";				
		}	
	}
	$groups .= "<p id='view'><a href='groups.php?u=$username'>View all</a></p>";
}

else{
	if($accountOwner == "yes"){
		$groups = "<p>You are not a member of any groups</p>";
		$groups .= "<p><a href='search.php'>Search for groups</a></p>";
	}
	else{
		$groups = "<p>$username is a member of no groups</p>";
	}
}


//code for report button
$reportButton = "";
if($log_username != $username){
	$reportButton = "<button id='reportButton'>Report</button>";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $username . " account"?></title>
	<script src="js/ajax.js" type="text/javascript"></script>
	<script src="jquery/jquery-1.11.2.min.js"></script>
	<script src="jquery/jquery-ui.min.js"></script>
	<link href="jquery/jquery-ui.min.css" type="text/css" rel="stylesheet">
	<link href="css/user.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="js/nav_bar.js"></script>
	<script type="text/javascript" src="js/user.js"></script>
	<script type="text/javascript" src="js/nav_bar.js"></script>
	
</head>
<body>
<div id="info">
	<div id="profilePic">
		<?php echo $profilePic; ?>
	</div>
	<?php 
		if($userType == "b"){
			echo "<h1>$username has been banned</h1>";
			if($admin == true){
				echo "<p>$banButton</p>";
			}
			exit();
		}
		$info = "<h1>$username</h1>";
		$info .= "<table border='1'>";
		$info .= "<tr><td>Gender</td><td>$gender</td></tr>";
		$info .= "<tr><td>Email</td><td>$email</td></tr>";
		$info .= "<tr><td>City</td><td>$city</td></tr>";
		$info .= "<tr><td>County</td><td>$county</td></tr>";
		$info .= "<tr><td>Last online</td><td>$lastOnline</td></tr>";
		$info .= "<tr><td>Date Joined</td><td>$dateJoined</td></tr>";
		if($accountOwner == "no"){
			$info .= "<tr><td>Friend</td><td><span id='friendButton'>$friendButton</span></td></tr>";
			$info .= "<tr><td>Block</td><td><span id='blockButton'>$blockedButton</span></td></tr>";
			$info .= "<tr><td>Send message</td><td><button id='messageButton'>Send Messsage</button>";
			$info .= "<div id='message'>";
			$info .= "<textarea rows='4' cols='40' name='messageInput' id='messageInput' placeholder='Enter you message...'></textarea><br>";
			$info .= "<span id='sendOperation'><button onclick=\"sendMessage('$log_username', '$username')\">Send Message</button><span>";
			$info .= "</div></td></tr>";
			if($admin == true){ 
				$info .= "<tr><td>Ban</td><td><span id='banButton'>$banButton</span></td></tr>";
			}
			$info .= "<tr><td colspan='2'>";
				$info .= "$reportButton";
				$info .= "<div id='report'>";
				$info .= "<div id='reportIssues'></div>";
				$info .= "<label for='reason'>Choose a reason</label>";
				$info .= "<select name='reason' id='reason'>";
				$info .= "<option value=''>Select</option>";
				$info .= "<option value='verbal'>Verbal abuse</option>";
				$info .= "</select>";
				$info .= "<br>";
				$info .= "<label for='explanation'>Explain your problem with this user</label><br>";
				$info .= "<textarea rows='4' cols='50' name='explanation' id='explanation'></textarea><br>";
				$info .= "<span id='reportOperation'><button onclick=\"sendReport('$log_username', '$username')\">Send report</button><span>";
				$info .= "</div>";
				$info .= "</td></tr>";
		}
		$info .= "</table>";
		echo $info;
	?>
</div>
<div id="posts">
	<h1>User activity</h1>
	<?php
		echo "<p>Total posts: <a href='posts.php?u=$username'>$allPostsNo</a></p>";
		echo "<p>Active posts: <a href='posts.php?u=$username#tab1'>$activePostsNo</a></p>";
		echo "<p>Pending posts: <a href='posts.php?u=$username#tab2'>$pendingPostsNo</a></p>";
		echo "<p>Withdrawn posts: <a href='posts.php?u=$username#tab3'>$withdrawnPostsNo</a></p>";
	?>
</div>
<div id="friends">
	<?php echo $friendDiv; ?>
</div>
<div id="groups">
	<?php echo $groups; ?>
</div>
</body>
</html>