<?php 
include_once("include/checkLoginStatus.php");
if(isset($_GET["u"])){
	$username = $_GET["u"];
}
else{
	header("location: http://localhost:8080/Project");
	exit();
}
$sql = "SELECT username FROM users WHERE username = '$username' LIMIT 1";
$query = mysql_query($sql);
$row = mysql_num_rows($query);
if($row < 1){
	header("location: http://localhost:8080/Project");
	exit();
}

else{
	$active = "";
	$pending = "";
	$withdrawn = "";

	//Code for active tab
	$sql = "SELECT * FROM items WHERE poster = '$username' AND status = 'a'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows < 1){
		$active = "<p>There are no posts in the active section</p>";
	}
	else{
		while($rows = mysql_fetch_array($query)){
			$id = $rows["id"];
			$itemName = $rows["itemName"];
			$image = $rows["image"];
			$poster = $rows["poster"];
			$description = $rows["description"];
			$category = $rows["category"];
			$subcategory = $rows["subcategory"];
			$price = $rows["price"];
			$shopName = $rows["shopName"];
			
			$active .= "<div class='post'>";
			if($image != null){
				$active .= "<a href='post.php?id=$id'><img src='$image' alt='$itemName'></a>";
			}
			else{
				$active .= "<a href='post.php?id=$id'><img src='images/notAvailable.jpg' alt='$itemName'></a>";
			}
			$active .= "<div class='leftSide'>";
			$active .= "<a href='post.php?id=$id'><h3>$itemName</h3></a>";
			$active .= "<p>Posted by: $poster</p>";
			$active .= "<p>$description</p>";
			$active .= "<p><a href='search.php?category=$category&subcategory='>$category</a> > <a href='search.php?category=$category&subcategory=$subcategory'>$subcategory</a></p>";
			$active .= "</div>";
			$active .= "<div class='rightSide'>";
			$active .= "<p>Price: &euro;$price</p>";
			$active .= "<p>Shop: $shopName</p>";
			if($log_username == $poster){
				$active .= "<p><a href='editPost.php?id=$id'>Edit post</a> / <button id='$id' onclick='withdraw(this.id)'>Withdraw</button></p>";
			}
			$active .= "</div>";
			$active .= "</div>";
		}
	}
	
	//Code for pending tab
	$sql = "SELECT * FROM items WHERE poster = '$username' AND status = 'p'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows < 1){
		$pending = "<p>There are no posts in the pending section</p>";
	}
	else{
		while($rows = mysql_fetch_array($query)){
			$id = $rows["id"];
			$itemName = $rows["itemName"];
			$image = $rows["image"];
			$poster = $rows["poster"];
			$description = $rows["description"];
			$category = $rows["category"];
			$subcategory = $rows["subcategory"];
			$price = $rows["price"];
			$shopName = $rows["shopName"];
			
			$pending .= "<div class='post'>";
			if($image != null){
				$pending .= "<a href='post.php?id=$id'><img src='$image' alt='$itemName'></a>";
			}
			else{
				$pending .= "<a href='post.php?id=$id'><img src='images/notAvailable.jpg' alt='$itemName'></a>";
			}
			$pending .= "<div class='leftSide'>";
			$pending .= "<a href='post.php?id=$id'><h3>$itemName</h3></a>";
			$pending .= "<p>Posted by: $poster</p>";
			$pending .= "<p>$description</p>";
			$pending .= "<p><a href='search.php?category=$category&subcategory='>$category</a> > <a href='search.php?category=$category&subcategory=$subcategory'>$subcategory</a></p>";
			$pending .= "</div>";
			$pending .= "<div class='rightSide'>";
			$pending .= "<p>Price: &euro;$price</p>";
			$pending .= "<p>Shop: $shopName</p>";
			if($log_username == $poster){
				$pending .= "<p><a href='editPost.php?id=$id'>Edit post</a> / <button id='$id' onclick='withdraw(this.id)'>Withdraw</button></p>";
			}
			$pending .= "</div>";
			$pending .= "</div>";
		}
	}
	
	//Code for withdrawn tab
	$sql = "SELECT * FROM items WHERE poster = '$username' AND status = 'w'";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	
	if($numRows < 1){
		$withdrawn = "<p>There are no posts in the withdrawn section</p>";
	}
	else{
		while($rows = mysql_fetch_array($query)){
			$id = $rows["id"];
			$itemName = $rows["itemName"];
			$image = $rows["image"];
			$poster = $rows["poster"];
			$description = $rows["description"];
			$category = $rows["category"];
			$subcategory = $rows["subcategory"];
			$price = $rows["price"];
			$shopName = $rows["shopName"];
			
			$withdrawn .= "<div class='post'>";
			if($image != null){
				$withdrawn .= "<a href='post.php?id=$id'><img src='$image' alt='$itemName'></a>";
			}
			else{
				$withdrawn .= "<a href='post.php?id=$id'><img src='images/notAvailable.jpg' alt='$itemName'></a>";
			}
			$withdrawn .= "<div class='leftSide'>";
			$withdrawn .= "<a href='post.php?id=$id'><h3>$itemName</h3></a>";
			$withdrawn .= "<p>Posted by: $poster</p>";
			$withdrawn .= "<p>$description</p>";
			$withdrawn .= "<p><a href='search.php?category=$category&subcategory='>$category</a> > <a href='search.php?category=$category&subcategory=$subcategory'>$subcategory</a></p>";
			$withdrawn .= "</div>";
			$withdrawn .= "<div class='rightSide'>";
			$withdrawn .= "<p>Price: &euro;$price</p>";
			$withdrawn .= "<p>Shop: $shopName</p>";
			if($log_username == $poster){
				$withdrawn .= "<p><button id='$id' onclick='repost(this.id)'>Repost</button></p>";
			}
			$withdrawn .= "</div>";
			$withdrawn .= "</div>";
		}
	}
}
?>
<?php include_once("include/header.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<script>
		$("document").ready(function(){
			$("#tabs").tabs();
		});
	</script>
	<style>
		h1{
			margin-top: 80px;
			text-align: center;
			color: #00BFFF;
		}
		
		#tabs{
			margin: 0 auto;
			overflow: auto;
			width: 90%;
		}		
		
		.post{
			overflow: auto;
			border: 2px solid #00BFFF;
			height: 220px;
			width: 90%;
			margin: 10px auto;
		}
		
		.post a{
			color: blue;
			text-decoration: none;
		}
		.post a:hover{
			text-decoration: underline;
		}
		.post button{
			background: none;
			border: none;
			text-decoration: underline;
			font-size: 18px;
			text-decoration: none;
			color: blue;
		}
		.post button:hover{
			text-decoration: underline;
		}
		
		.post img{
			width: 20%;
			max-height: 200px;
			float: left;
			margin: 1%;
		}
		.leftSide{
			width: 50%;
			padding: 1%;
			float: left;
		}
		
		.rightSide{
			width: 16%;	
			padding: 1%;
			float: right;	
			text-align: center;
		}
	</style>
	<script>
		function withdraw(id){
			$("#dialog").attr("title", "Withdraw Ad").text("Are your sure you want to withdraw this ad?").dialog({ buttons: {"Yes": function(){
				$.post("parsers/postSystem.php", {id: id, withdraw: "withdraw"}, function(data){
				});
				location.reload();
				window.location.assign("http://localhost:8080/Project/posts.php?u=" + '<?php echo $log_username; ?>' + "#tab3");
				$(this).dialog('close');
				
				
			}, "Cancel": function(){
				$(this).dialog("close");
				alert("btye");
			}
			} , modal: true });
		}
		function repost(id){
			$("#dialog").attr("title", "Withdraw Ad").text("Are your sure you want to withdraw this ad?").dialog({ buttons: {"Yes": function(){
				
			}, "Cancel": function(){
				$(this).dialog("close");
			}
			} , modal: true });
		}
	</script>
</head>
<body>
<?php
	if($log_username == $username){
		echo "<h1>Your posts</h1>";
	}
	else{
		echo "<h1>$username posts</h1>";
	}
?>
<div id="tabs">
	<ul>
		<li><a href="#tab1">Active</a></li>
		<li><a href="#tab2">Pending</a></li>
		<li><a href="#tab3">Withdrawn</a></li>	
	</ul>	
	<div id="tab1">
		<?php echo $active; ?>
	</div>
	<div id="tab2">
		<?php echo $pending; ?>
	</div>
	<div id="tab3">
		<?php echo $withdrawn; ?>
	</div>	
</div>
<div id="dialog"></div>
</body>
</html>