<?php 
include_once("include/checkLoginStatus.php");
include_once("include/header.php");
include_once("include/db_connect.php");
if($loggedIn != true && $admin != true){
	header("Location: http://localhost:8080/Project/index.php");
	exit();
}

$posts = "";

$sql = "SELECT * FROM items WHERE status = 'p'";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);

if($numRows < 1){
	$posts = "<p>There are no posts that need to be aproved</p>";
}
else{
	while($rows = mysql_fetch_array($query)){
		$id = $rows["id"];
		$itemName = $rows["itemName"];
		$poster = $rows["poster"];
		$description = $rows["description"];
		$category = $rows["category"];
		$subCategory = $rows["subcategory"];
		$price = $rows["price"];
		$shopName = $rows["shopName"];
		
		$posts .= "<div class='post'>";
		$posts .= "<div class='leftSide'>";
		$posts .= "<p>$itemName</p>";
		$posts .= "<p>Posted by: $poster</p>";
		$posts .= "<p>$description</p>";
		$posts .= "<p>$category > $subCategory</p>";
		$posts .= "</div>";
		$posts .= "<div class='rightSide'>";
		$posts .= "<p>Price: &euro;$price</p>";
		$posts .= "<p>Shop: $shopName</p>";
		$posts .= "<span id='$id'>";
		$posts .= "<button onclick=\"adminSelection($id, 'approve')\">Approve</button></p>";
		$posts .= "<p><button onclick=\"adminSelection($id, 'disapprove')\">Disapprove</button></p>";
		$posts .= "</span>";
		$posts .= "</div>";
		$posts .= "</div>";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
	function adminSelection(id, choice){
		$.post("parsers/postApproval.php", {postId: id, postChoice: choice}, function(data){
			if(data == "approved"){
				$("#" + id).html("<p>Post approved!</p>");
			}
			else if(data == "disapproved"){
				$("#" + id).html("Post not approved!");
			}
			else{
				alert(data);
				$("#" + id).html("Try again later!");
			}
		});
	}
</script>
<style>
#posts{
	margin-top: 80px;
}

.post{
	border: 2px solid #00BFFF;
	height: 220px;
	width: 90%;
	margin: 10px auto;
	overflow: auto;
}
.leftSide{
	width: 80%;
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
</head>
<body>
<div id="posts">
	<?php echo $posts ?>
</div>
</body>
</html>











