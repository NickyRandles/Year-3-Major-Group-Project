<?php 
error_reporting(E_ALL ^ E_WARNING);
include_once("include/header.php"); 
include_once("include/checkLoginStatus.php");
if($loggedIn != true){
	header("location: http://localhost:8080/Project/");
	exit();
}

if(isset($_GET["id"])){
	$id = $_GET["id"];
}
else{
	header("location: http://localhost:8080/Project/");
}

$sql = "SELECT * FROM items WHERE id = '$id' AND poster = '$log_username' LIMIT 1";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);

if($numRows < 1){
	header("location: http://localhost:8080/Project/");
	exit();
}
else{
	while($row = mysql_fetch_array($query)){
		$itemName = $row["itemName"];
		$image = $row["image"];
		$description = $row["description"];
		$price = $row["price"];
		$shopName = $row["shopName"];
		$category = $row["category"];
		$subCategory = $row["category"];
		
	}
}

$error = "";
$message = "";
$filePath = "";
if(isset($_POST['submit'])){
	$itemName = $_POST["itemName"];
	$description = $_POST["description"];
	$price = $_POST["price"];
	$shopName = $_POST["shopName"];
	$category = $_POST["category"];
	$subCategory = $_POST["subCategory"];
	
	$reg = "/^[a-zA-Z0-9]/";
	$reg2 = "/^[0-9][.]?[0-9]{0,2}?$/";
	$reg3 = "/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/";
	
	if(empty($itemName)){
		$error .= "<p class='error'>Item name must be entered</p>";
	}
	else if (!preg_match($reg, $itemName)){
		$error .= "<p class='error'>Item name must contain letters and numbers only</p>";
	}
	if(empty($description)){
		$error .= "<p class='error'>Description must be entered</p>";
	}
	if(empty($price)){
		$error .= "<p class='error'>Price must be entered</p>";
	}
	else if (!preg_match($reg2, $price)){
		$error .= "<p class='error'>Price must be a number</p>";
	}
	
	if(isset($_FILES["itemImage"])){
		if(!empty($_FILES["itemImage"]["name"])){
			$allowed = array("jpg", "jpeg", "png", "gif");
			$fileName = $_FILES["itemImage"]["name"];
			$fileExt =  strtolower(end(explode(".", $fileName)));
			$fileTemp = $_FILES["itemImage"]["tmp_name"];
			
			if(in_array($fileExt, $allowed)){
				$filePath = "posts/" . substr(md5(time()), 0, 10) . "." . $fileExt;
				move_uploaded_file($fileTemp, $filePath);
				$sql = "UPDATE items SET image = '$filePath' WHERE id = '$id' LIMIT 1";
				$query = mysql_query($sql);
			}
			else{
				$errors .= "<p class='error'>Invalid valid type. File types allowed: " . implode(", ", $allowed) . "</p>";
			}
		}
	}
	
	if($error == ""){
		$sql = "UPDATE items SET itemName = '$itemName', description = '$description', price = '$price', category = '$category',
		subCategory = '$subCategory', shopName = '$shopName', lastUpdated = now() WHERE id = '$id' LIMIT 1";
		$query = mysql_query($sql);	
		
		$message .= "<p class='message'>Your post has been successfully edited! It is now pending!</p>";
		$message .= "<p class='message'><a href='posts.php?u=$log_username'>Click here</a> to go to your posts</p>";
	}	
}
?>
<!DOCYPE html>
<html>
<head>
	<link rel="shortcut icon" href="images/icon.png"/>
	<link href="css/register.css" type="text/css" rel="stylesheet">
</head>
<body>
<div id="registerForm">
	<h1>Edit Post</h1>
	<?php
		echo $error;
		echo $message;
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<?php
			if($filePath != null){
				echo "<img src='$filePath' alt='$itemName'>";
			}
			else if($image != null){
				echo "<img src='$image' alt='$itemName'>";
			}
			else{
				echo "<img src='images/notAvailable.jpg' alt='$itemName'>";
			}
		?>
		<div>
			<label for="itemImage">Change image:</label>
			<input type='file' name='itemImage'>
		</div>
		<div>
			<label for="itemName">Item name:</label>
			<input type="text"= name="itemName" value="<?php echo $itemName ?>">
		</div>
		<div>
			<label for="description">Description:</label>
			<textarea name="description"><?php echo $description ?></textarea>
		</div>
		<div>	
			<label for="price">Price:</label>
			<input type="text"= name="price" value="<?php echo $price ?>">
		</div>
		<div>	
			<label for="shopName">Shop name:</label>
			<select name="shopName">
				<option value="ALDI">ALDI</option>
				<option value="LIDL">LIDL</option>
				<option value="Tesco">Tesco</option>
				<option value="Dunnes">Dunnes</option>
			</select>
		</div>
		<div>	
			<label for="category">Category:</label>
			<select name="category">
				<option value="dairy">Dairy</option>
				<option value="fruit">Fruit</option>
			</select>
		</div>
		<div>	
			<label for="subCategory">Sub-Category:</label>
			<select name="subCategory">
				<option value="Milk">Milk</option>
				<option value="Cheese">Cheese</option>
				<option value="Bananas">Bananas</option>
				<option value="Apples">Apples</option>
			</select>
		</div>

		<input type="submit" value="Add" name="submit">
	</form>
</div>
</body>
</html>
