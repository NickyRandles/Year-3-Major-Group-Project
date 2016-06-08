<?php
include_once("include/checkLoginStatus.php");
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
else{
	header("location: http://localhost:8080/Project");
	exit();
}

$post = "";
$sql = "SELECT * FROM items WHERE id = '$id' LIMIT 1";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);

if($numRows > 0){
	$row = mysql_fetch_array($query);
	$itemName = $row["itemName"];
	$image = $row["image"];
	$description = $row["description"];
	$price = $row["price"];
	$category = $row["category"];
	$subcategory = $row["subcategory"];
	$shopName = $row["shopName"];
	$poster = $row["poster"];
	$postTime = $row["postTime"];
	$lastUpdated = $row["lastUpdated"];
	
	$post .= "<div id='post'>";
	if($image != null){
		$post .= "<img src = '$image' alt='$itemName'>";
	}
	else{
		$post .= "<img src = 'images/notAvailable.jpg' alt='$itemName'>";
	}
	$post .= "<div id='basicInfo'>";
	$post .= "<h1>$itemName</h1>";
	$post .= "<p>Posted by: <a href='user.php?u=$poster'>$poster</a></p>";
	$post .= "<p>Price: &euro;$price</p>";
	$post .= "<p>Shop: $shopName</p>";
	$post .= "<p>Posted on: $postTime</p>";
	$post .= "<p>Last updated: $lastUpdated</p>";
	$post .= "<p>Category: <a href='search.php?category=$category&subcategory='>$category</a> > <a href='search.php?category=$category&subcategory=$subcategory'>$subcategory</a></p>";
	if($loggedIn == true){
		$post .= "<label for='option'>Let people know:</label>";
		$post .= "<select name='option' id='option' onchange='notify()'>";
		$post .= "<option value=''>Select</option>";
		$post .= "<option value='friend'>Friend</option>";
		$post .= "<option value='group'>Group</option>";
		$post .= "</select>";

		$post .= "<div id='suggestions'></div>";
		$post .= "<div id='notify'>";
		$post .= "<div>";
		$post .= "<label for='input'>Name:</label>";
		$post .= "<input type='text' name='input' id='input' onkeyup=\"suggestions()\">";
		$post .= "</div>";
		$post .= "<div>";
		$post .= "<label for='message'>Message:</label>";
		$post .= "<textarea name='message' id='message'></textarea>";
		$post .= "<button onclick=\"add('$log_username')\" id='add'>Add</button>";
		$post .= "<div id='response'></div>";
		$post .= "</div>";
		$post .= "</div>";		
	}
	if($log_username == $poster){
		$post .= "<p><a href='editPost.php?id=$id'>Edit post</a> / ";
		$post .= "<button id='withdraw' onclick='withdraw()'>Withdraw</button></p>";
	}
	
	$post .= "</div>";
	$post .= "<div id='description'>";
	$post .= "<h3>Description:</h3>";
	$post .= "<p>$description</p>";
	$post .= "</div>";
	$post .= "<h2>Comments</h2>";
	$post .= "<div id='comments'>";
	$sql = "SELECT * FROM comments WHERE postId = '$id' ORDER by postTime ASC";
	$query = mysql_query($sql);
	$numRows = mysql_num_rows($query);
	if($numRows > 0){
		while($row = mysql_fetch_array($query)){
			$username = $row["username"];
			$comment = $row["comment"];
			$postTime = $row["postTime"];
			
			$sql2 = "SELECT profilePic FROM users WHERE username = '$username' LIMIT 1";
			$query2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($query2);
			$profilePic = $row2["profilePic"];
			
			$post .= "<div id='userComment'>";
			$post .= "<div id='leftSide'>";
			if($profilePic != null){
				$post .= "<a href='user.php?u=$username'><img src='$profilePic' alt='$username'></a>";
			}
			else{
				$post .= "<a href='user.php?u=$username'><img src='images/blankProfile.jpg' alt='$username'></a>";
			}
			$post .= "<a href='user.php?u=$username'><h3>$username</h3></a>";
			$post .= "<p>$comment</p>";
			$post .= "</div>";
			$post .= "<div id='rightSide'>";
			$post .= "<p>$postTime</p>";
			$post .= "<button onclick='reply(\"$username\")'>Reply</button>";
			$post .= "</div>";
			$post .= "</div>";
			
		}
	}
	else{
		$post .= "<p id='noComments'>No comments about this post</p>";
	}
	$post .= "</div>";
	$post .= "<form action='post.php?id=$id' method='post'>";
	$post .= "<textarea name='comment' id='comment' placeholder='Enter your comment'></textarea>";
	$post .= "<input type='submit' name='submit' value='Post' id='submit'>";
	$post .= "</form>";
	$post .= "</div>";
	
	

}
else{
	header("location: http://localhost:8080/Project");
	exit();
}

if(isset($_POST["submit"])){
	$comment = $_POST["comment"];
	if($comment == null){
		exit();
	}
	$sql = "INSERT INTO comments(postId, username, comment, postTime) VALUES('$id', '$log_username', '$comment', now())";
	$query = mysql_query($sql);
	header("location: post.php?id=$id");

}

?>
<?php include_once("include/header.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<style>
		#post{
			margin-top: 80px;
		}
		#post img{
			width: 40%;
			float: left;
		}
		#post h2{
			text-align: center;
			color: blue; 
		}
		#basicInfo{
			width: 40%;
			padding-left: 10%;
			padding-top: 20px;
			border-left: 5px solid #00BFFF; 
			float: right;
		}
		#basicInfo a{
			text-decoration: none;
		}
		#basicInfo a:hover{
			text-decoration: underline;
		}
		#withdraw{
			background: none;
			border: none;
			text-decoration: underline;
			font-size: 16px;
			text-decoration: none;
			color: blue;
		}
		#basicInfo button:hover{
			text-decoration: underline;
		}
		#basicInfo select{
			margin-left: 5px;
			font-size: 16px;
		}
		#description{
			clear: both;
			width: 80%;
			margin: 0 auto;
			margin-bottom: 40px;
			padding: 1%;
			border: 2px solid #00BFFF;
		}
		#comments{
			overflow: auto;
			border-top: 5px solid #00BFFF;
			width: 80%;
			margin: 0 auto;
			background-color: #e9eaed;
			min-height: 100;
			max-height: 1000px;
		}
		#userComment{
			width: 80%;
			margin: 0 auto;
			background-color: white;
			border: 1px solid black;
			overflow: auto;
		}
		#leftSide{
			width: 70%;
			float: left;
		}
		#rightSide{
			width: 30%;
			float: right;
		}
		#leftSide img{
			width: 15%;
		}
		#leftSide p{
			width: 65%;
		}
		#rightSide span{
			
		}		
		#comment{
			width: 60%;
			height: 60px;
			margin-left: 20%;
			float: left; 
		}
		#submit{
			float: left; 
			width: 10%;
			padding: 0.5%;
			margin-top: 10px;
			margin-left: 2%;
			border: 2px solid #e9eaed;
			background-color: white;
			color: blue;
		}
		#noComments{
			text-align: center;
			font-size: 20px;
		}
		
		#suggestions{
			position: absolute;
			width: 21%;
			padding: 5px;
			margin-top: 37px;
			margin-left: 6.5%;
		}

		#suggestions li{
			overflow: auto;
			list-style-type: none;
			border: 1px solid blue;
			background-color: cyan;
			padding: 5px;
			width: 75%;
		}
		
		#suggestions li img{
			width: 20%;
			max-height: 40px;
		}
		
		#notify{
			margin-top: 2px;
			border: 1px solid #00BFFF;
			width: 60%;
			padding: 2%;
		}
		
		#notify div{
			margin-top: 5px;
		}
		
		#notify label{
			display: block;
			width: 25%;
			float: left;
		}
		
		#notify input{
			width: 70%;
			font-size: 16px;
			margin-top: -2px;
		}
		
		#notify textarea{
			width: 70%;
			height: 80px;
			font-size: 16px;
			margin-top: -2px;
		}
		
		#notify button{
			width: 20%;
			padding: 5px;
			margin-left: 50%;
		}
		
		#notify p{
			margin-left: 25%;
			text-align: center;
		}
	</style>
	<script>
	$(document).ready(function(){
			$("#notify").hide();	
			
	});
	
	function notify(){
		var option = document.getElementById("option").value;
			if(option != ""){
				$("#notify").show("blind");
			}
			else if(option == ""){
				$("#notify").hide("blind");
			}
	}
	
	function add(log_username){
		var option = document.getElementById("option").value;
		var name = $("#input").val();
		var message = $("#message").val();
		var url = encodeURIComponent(window.location);
		
		if(name == "" || message == ""){
			$("#response").html("<p>Please fill in fields first</p>");
			return false;
		}
		$.post("parsers/postSystem.php", {type: option, initiator: log_username, name: name, message: message, link: url}, function(data){
			if(data == "addSuccess"){
				$("#response").html("<p>Nofication send</p>");
				$("#input").val("");
				$("#message").val("");
			}
			else{
				$("#response").html("<p>" + data + "</p>");
			}
		});		
	}
	
	function suggestions(){
		var input = $("#input").val();
		var type = document.getElementById("option").value;

		if(input == ""){
			$("#suggestions").html("");
		}
		else{
			$.post("parsers/postSystem.php", {input: input, type: type}, function(data){
				$("#suggestions").html(data);
				
				$("#suggestions li").click(function(){
					var pick = $(this).text();
					$("#input").val(pick);
					$("#suggestions").html("");
				});
			});
		}
	}
	
	function withdraw(){
		$("#dialog").attr("title", "Withdraw Ad").text("Are your sure you want to withdraw this ad?").dialog({ buttons: {"Yes": function(){
			$.post("parsers/postSystem.php", {id: '<?php echo $id; ?>', withdraw: "withdraw"}, function(data){
			});
			window.location.assign("http://localhost:8080/Project/posts.php?u=" + '<?php echo $log_username; ?>');
			$(this).dialog('close');
		}, "Cancel": function(){
			$(this).dialog("close");
		}
		} , modal: true });
	}
	
	function reply(username){
		$("#comment").append("@" + username + " ");
	}
	
	</script>
</head>
<body>
<?php 
	echo $post;
?>
<div id="dialog"></div>
</body>
</html>