<?php
include_once("include/db_connect.php");

$output = "";
if(isset($_GET["search"]) && $_GET["type"] == "item"){
	$searchQ = $_GET["search"];
	$output .= "<h2>Search results for item: $searchQ</h2>";
	$sql = "SELECT * FROM items WHERE itemName LIKE '%$searchQ%' AND status = 'a' OR description LIKE '%$searchQ%' AND status = 'a' OR category LIKE '%$searchQ%' AND status = 'a' OR subcategory LIKE '%$searchQ%' AND status = 'a' ORDER by price ASC";
	$query = mysql_query($sql);
	$count = mysql_num_rows($query);
	
	if($count < 1){
		$output = "<p id='noResults'>No search results found<p>";
	}
	
	else{
		while($row = mysql_fetch_array($query)){
			$id = $row["id"];
			$itemName = $row["itemName"];
			$image = $row["image"];
			$description = $row["description"];
			$price = $row["price"];
			$category = $row["category"];
			$subcategory = $row["subcategory"];
			$shopName = $row["shopName"];
			$poster = $row["poster"];
			$postTime = get_timeago(strtotime($row["postTime"]));
			$lastUpdated = $row["lastUpdated"];
			
			$output .= "<div class='results'>";
			$output .= "<div class='leftSide'>";
			if($image != null){
				$output .= "<a href='post.php?id=$id'><img src='$image' alt='$itemName'></a>";
			}
			else{
				$output .= "<a href='post.php?id=$id'><img src='images/notAvailable.jpg' alt='$itemName'></a>";
			}
			$output .= "<a href='post.php?id=$id'><h3>$itemName</h3></a>";
			$output .= "<p>Posted by: $poster</p>";
			$output .= "<p>$description</p>";
			$output .= "<p><a href='search.php?category=$category&subcategory='>$category</a> > <a href='search.php?category=$category&subcategory=$subcategory'>$subcategory</a></p>";
			$output .= "</div>";
			$output .= "<div class='rightSide'>";
			$output .= "<p>Price: $price</p>";
			$output .= "<p>Shop: $shopName</p>";
			$output .= "<p>Posted:<br>$postTime</p>";
			$output .= "</div>";
			$output .= "</div>";
		}
	}
}	
else if(isset($_GET["search"]) && $_GET["type"] == "person"){
	$searchQ = $_GET["search"];
	$output .= "<h2>Search results for person: $searchQ</h2>";
	
	$sql = "SELECT * FROM users WHERE username LIKE '%$searchQ%'";
	$query = mysql_query($sql);
	$count = mysql_num_rows($query);
	
	if($count < 1){
		$output = "<p id='noResults'>No search results found<p>";
	}
	
	else{
		while($row = mysql_fetch_array($query)){
			$username = $row["username"];
			$firstName = $row["firstName"];
			$lastName = $row["lastName"];
			$email = $row["email"];
			$gender = $row["gender"];
			$city = $row["city"];
			$county = $row["city"];
			$image = $row["profilePic"];
			$signedUp = get_timeago(strtotime($row["signUp"]));
			$lastOnline = get_timeago(strtotime($row["lastLogin"]));
			
			$output .= "<div class='results'>";
			$output .= "<div class='leftSide'>";
			if($image != null){
				$output .= "<a href='user.php?u=$username'><img src='$image' alt='$username'></a>";
			}
			else{
				$output .= "<a href='user.php?u=$username'><img src='images/blankProfile.jpg' alt='$username'></a>";
			}
			$output .= "<a href='user.php?u=$username'><h3>$username</h3></a>";
			$output .= "<p>Name: $firstName $lastName</p>";
			$output .= "<p>Email: <a href='mailto:$email'>$email</a></p>";
			$output .= "<p>From: $city, Co. $county</p>";
			$output .= "</div>";
			$output .= "<div class='rightSide'>";
			if($gender == "m"){
				$output .= "<p>Gender: Male</p>";
			}
			else{
				$output .= "<p>Gender: Female</p>";
			}
			$output .= "<p>Signed up: $signedUp</p>";
			$output .= "<p>Last online: $lastOnline</p>";
			$output .= "</div>";
			$output .= "</div>";
		}
	}
}	
else if(isset($_GET["search"]) && $_GET["type"] == "group"){
	$searchQ = $_GET["search"];
	$output .= "<h2>Search results for group: $searchQ</h2>";
	
	$sql = "SELECT * FROM groups WHERE name LIKE '%$searchQ%'";
	$query = mysql_query($sql);
	$count = mysql_num_rows($query);
	
	if($count < 1){
		$output = "<p id='noResults'>No search results found<p>";
	}
	
	else{
		while($row = mysql_fetch_array($query)){
			$id = $row["id"];
			$name = $row["name"];
			$description = $row["description"];
			$creator = $row["creator"];
			$image = $row["image"];
			$dateCreated = date("F j, Y", strtotime($row["dateCreated"]));  
		}
		
		$output .= "<div class='results'>";
		$output .= "<div class='leftSide'>";
		if($image != null){
			$output .= "<a href='group.php?id=$id'><img src='$image' alt='$name'></a>";
		}
		else{
			$output .= "<a href='group.php?id=$id'><img src='images/notAvailable.jpg' alt='$name'></a>";
		}
		$output .= "<a href='group.php?id=$id'><h3>$name</h3></a>";
		$output .= "<p>Created by: $creator</p>";
		$output .= "<p>$description</p>";
		$output .= "</div>";
		$output .= "<div class='rightSide'>";
		$output .= "<p>Date Created:<br> $dateCreated</p>";
		$output .= "</div>";
		$output .= "</div>";
		
		
	}
}	
else if(isset($_GET["category"]) && $_GET["subcategory"] == null){
	$category = $_GET["category"];
	$output .= "<h2>Search results for category: $category</h2>";
	
	$sql = "SELECT * FROM items WHERE category = '$category' AND status = 'a' ORDER by price ASC";
	$query = mysql_query($sql);
	$count = mysql_num_rows($query);
	
	if($count < 1){
		$output = "<p id='noResults'>No search results found<p>";
	}
	
	else{
		while($row = mysql_fetch_array($query)){
			$id = $row["id"];
			$itemName = $row["itemName"];
			$image = $row["image"];
			$description = $row["description"];
			$price = $row["price"];
			$category = $row["category"];
			$subcategory = $row["subcategory"];
			$shopName = $row["shopName"];
			$poster = $row["poster"];
			$postTime = get_timeago(strtotime($row["postTime"]));
			
			$output .= "<div class='results'>";
			$output .= "<div class='leftSide'>";
			if($image != null){
				$output .= "<a href='post.php?id=$id'><img src='$image' alt='$itemName'></a>";
			}
			else{
				$output .= "<a href='post.php?id=$id'><img src='images/notAvailable.jpg' alt='$itemName'></a>";
			}
			$output .= "<a href='post.php?id=$id'><h3>$itemName</h3></a>";
			$output .= "<p>Posted by: $poster</p>";
			$output .= "<p>$description</p>";
			$output .= "<p><a href='search.php?category=$category&subcategory='>$category</a> > <a href='search.php?category=$category&subcategory=$subcategory'>$subcategory</a></p>";
			$output .= "</div>";
			$output .= "<div class='rightSide'>";
			$output .= "<p>Price: $price</p>";
			$output .= "<p>Shop: $shopName</p>";
			$output .= "<p>Posted:<br>$postTime</p>";
			$output .= "</div>";
			$output .= "</div>";
		}
	}
}	
else if(isset($_GET["category"]) && isset($_GET["subcategory"])){
	$category = $_GET["category"];
	$subcategory = $_GET["subcategory"];
	$output .= "<h2>Search results for: $category > $subcategory</h2>";
	
	$sql = "SELECT * FROM items WHERE category = '$category' AND subcategory = '$subcategory' AND status = 'a' ORDER by price ASC";
	$query = mysql_query($sql);
	$count = mysql_num_rows($query);
	
	if($count < 1){
		$output = "<p id='noResults'>No search results found<p>";
	}
	
	else{
		while($row = mysql_fetch_array($query)){
			$id = $row["id"];
			$itemName = $row["itemName"];
			$image = $row["image"];
			$description = $row["description"];
			$price = $row["price"];
			$category = $row["category"];
			$subcategory = $row["subcategory"];
			$shopName = $row["shopName"];
			$poster = $row["poster"];
			$postTime = get_timeago(strtotime($row["postTime"]));
			
			$output .= "<div class='results'>";
			$output .= "<div class='leftSide'>";
			if($image != null){
				$output .= "<a href='post.php?id=$id'><img src='$image' alt='$itemName'></a>";
			}
			else{
				$output .= "<a href='post.php?id=$id'><img src='images/notAvailable.jpg' alt='$itemName'></a>";
			}
			$output .= "<a href='post.php?id=$id'><h3>$itemName</h3></a>";
			$output .= "<p>Posted by: $poster</p>";
			$output .= "<p>$description</p>";
			$output .= "<p><a href='search.php?category=$category&subcategory='>$category</a> > <a href='search.php?category=$category&subcategory=$subcategory'>$subcategory</a></p>";
			$output .= "</div>";
			$output .= "<div class='rightSide'>";
			$output .= "<p>Price: $price</p>";
			$output .= "<p>Shop: $shopName</p>";
			$output .= "<p>Posted on:<br>$postTime</p>";
			$output .= "</div>";
			$output .= "</div>";
		}
	}
}	
else{
	$output = "<p id='noResults'>Enter what you are looking for in the search bar above</p>";
}
function get_timeago( $ptime )
{
    $estimate_time = time() - $ptime;

    if( $estimate_time < 1 )
    {
        return 'less than 1 second ago';
    }

    $condition = array( 
                12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $estimate_time / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Shop and Save</title>
	<link rel="shortcut icon" href="images/icon.png"/>
	<link href="css/search.css" type="text/css" rel="stylesheet">
</head>
<body>
<?php include_once("include/header.php") ?>
<div id="allResults">
	<?php echo "$output" ?>
</div>
</body>
</html>