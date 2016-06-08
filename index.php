<?php
include_once("include/header.php");
if($loggedIn == true){
	$output = "<h2>People you may know</h2>";
	$i = 0;
	$output .= "<table>";
	$sql = "SELECT city, county from users WHERE username = '$log_username' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	$userCity = $row["city"];
	$userCounty = $row["county"];
	
	$sql = "SELECT * FROM friends WHERE asked = '$log_username' AND accepted = 'y'";
	$query1 = mysql_query($sql);
	
	$sql = "SELECT * FROM friends WHERE asker = '$log_username' AND accepted = 'y'";
	$query2 = mysql_query($sql);
	
	$friends = array();
	
	while($row1 = mysql_fetch_array($query1)){
		array_push($friends, $row1["asker"]);
	}
	while($row2 = mysql_fetch_array($query2)){
		array_push($friends, $row2["asked"]);
	}
	
	$sql = "SELECT * FROM users";
	$query = mysql_query($sql);

	$output .= "<table>";
	while($allUsers = mysql_fetch_array($query)){
		$user = $allUsers["username"];
		if(!in_array($user, $friends) && $user != $log_username){
			if($i < 6){
				$sql2 = "SELECT * from users WHERE username = '$user' LIMIT 1";
				$query2 = mysql_query($sql2);
				$row = mysql_fetch_array($query2);
				$picture = $row["profilePic"];
				$name = $row["firstName"] . " ". $row["lastName"];
				$address = "From " . $row["city"] . "<br>" . "Co. " . $row["county"];
				
				if($i % 3 == 0){
					$output .= "<tr>";
					if(!empty($picture)){
						$output .= "<td>";
						$output .= "<a href='user.php?u=$user'><img src='$picture' title='$user'></a>";
						$output .= "<p><a href='user.php?u=$user'>$name</a>";
						$output .= "<br>$address</p>";
						$output .= "</td>";
					}
					else{
						$output .= "<td>";
						$output .= "<a href='user.php?u=$user'><img src='images/blankProfile.jpg' title='$user'></a>";
						$output .= "<p><a href='user.php?u=$user'>$name</a>";
						$output .= "<br>$address</p>";
						$output .= "</td>";
					}
				}
				else{
					if(!empty($picture)){
						$output .= "<td>";
						$output .= "<a href='user.php?u=$user'><img src='$picture' title='$user'></a>";
						$output .= "<p><a href='user.php?u=$user'>$name</a>";
						$output .= "<br>$address</p>";
						$output .= "</td>";
					}
					else{
						$output .= "<td>";
						$output .= "<a href='user.php?u=$user'><img src='images/blankProfile.jpg' title='$user'></a>";
						$output .= "<p><a href='user.php?u=$user'>$name</a>";
						$output .= "<br>$address</p>";
						$output .= "</td>";
					}
				}			
			}
			$i++;	
		}	
	}
	$output .= "</tr></table>";
}
else{
	$output = "<h2>About Shop and Save</h2><p>This website has been designed to help you save money on your shopping and make shopping a lot more hassle free. This is a price comparison website that compares the prices of items in different supermarkets. This site gives you the ability to enter in the item you are looking for and the your location. It will then display all the supermarkets in your area with that item and rank them from lowest to highest cost. This website also helps you to find supermarkets all over Ireland. Go on to our 'find supermarkets page' and enter in in your location and the store you are looking for. It will then display all of the stores near you.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Shop and Save</title>
	<link rel="shortcut icon" href="images/icon.png"/>
	<link href="css/index.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="js/nav_bar.js"></script>
	<style>
		#info{
			font-family: Arial, Helvetica, sans-serif;font-family: Arial, Helvetica, sans-serif;
			
		}
		#info p{
			font-size: 18px;
		}
		h2{
			text-align: center;
		}
		table{
			width: 100%;
			border-spacing: 15px;
		}
		table td{
			border: 1px solid #00BFFF;
			height: 60px;
			width: 33%;
		}
		table img{
			padding: 2px;
			height: 100%;
			width: 20%;
			float: left;
		}
		table p{
			float: left;
			margin-left: 5%;
		}
	</style>
</head>
<body>
<div id = "photoShow">
	<div class = "current">
		<a href="search.php"><img src = "images/slideshow/search.jpg" alt = "Search" height = "360" width  = "500" class = "photoGallery"></a>
	</div>
	<div>
		<a href="categories.php"><img src = "images/slideshow/categories.jpg" alt = "Categories" height = "360" width  = "500" class = "photoGallery"></a>
	</div>
	<div>
		<a href="find.php"><img src = "images/slideshow/maps.jpg" alt = "Maps" height = "360" width  = "500" class = "photoGallery"></a>
	</div>
	<div>
		<a href="shops.php"><img src = "images/slideshow/shops.jpg" alt = "Shops" height = "360" width  = "500" class = "photoGallery"></a>
	</div>
</div>
<div id="info">
<?php echo $output?>
</div>
<footer>
	<hr>
	&copy; Shop and Save 2014
</footer>
<script>
$(document).ready(function(){
	setInterval("imageRotator()",3000);
});

function imageRotator(){
	
	var currPhoto = $("#photoShow div.current");
	var nextPhoto = currPhoto.next();
	
	if(nextPhoto.length == 0)
	{
		nextPhoto = $("#photoShow div:first")
	}
	
	currPhoto.removeClass('current').addClass('previous');
	
	nextPhoto.css({ opacity: 0.0 }).addClass('current').animate({ opacity: 1.0 }, 2000,
	function() {
		currPhoto.removeClass('previous');
	});
}
</script>
</body>
</html>