<?php
error_reporting(E_ALL ^ E_WARNING);
include_once('include/checkLoginStatus.php');
if($loggedIn != true){
	header("location: http://localhost:8080/Project/index.php");
	exit();
}

$errors = "";
$message = "";
$itemName = "";
$description = "";
$price = "";
$shopName = "";
$category = "";
$subCategory = "";
if(isset($_POST['submit'])){
	$itemName = $_POST["itemName"];
	$description = htmlentities($_POST["description"]);
	$price = $_POST["price"];
	$shopName = $_POST["shopName"];
	$category = $_POST["category"];
	$subCategory = $_POST["subCategory"];
	
	$reg1 = "/^[a-zA-Z0-9_ ]*$/";
	$reg2 = "/^[0-9][.]?[0-9]{0,2}?$/";
	
	if(empty($itemName)){
		$errors .=  "<p class='error'>Please fill in the item name</p>";
	}
	else if(!preg_match($reg1, $itemName)){
		$errors .=  "<p class='error'>Item name must be characters and numbers only</p>";
	}
	if(empty($description)){
		$errors .=  "<p class='error'>Please fill in the item description</p>";
	}
	if(empty($price)){
		$errors .=  "<p class='error'>Please fill in the item price</p>";
	}
	else if(!preg_match($reg2, $price)){
		$errors .=  "<p class='error'>Price must be numbers only</p>";
	}
	
	$filePath = "";
	if(isset($_FILES["itemImage"])){
		if(!empty($_FILES["itemImage"]["name"])){
			$allowed = array("jpg", "jpeg", "png", "gif");
			$fileName = $_FILES["itemImage"]["name"];
			$fileExt =  strtolower(end(explode(".", $fileName)));
			$fileTemp = $_FILES["itemImage"]["tmp_name"];
			
			if(in_array($fileExt, $allowed)){
				$filePath = "posts/" . substr(md5(time()), 0, 10) . "." . $fileExt;
				move_uploaded_file($fileTemp, $filePath);
			}
			else{
				$errors .= "<p class='error'>Invalid valid type. File types allowed: " . implode(", ", $allowed) . "</p>";
			}
		}
	}
	
	if($errors == ""){
		$sql = "INSERT INTO items(itemName, image, description, price, category, subCategory, shopName, poster, postTime, lastUpdated)
				VALUES('$itemName', '$filePath', '$description', '$price', '$category', '$subCategory', '$shopName', '$log_username', now(), now())";
			
		$query = mysql_query($sql);	
		
		$itemName = "";
		$description = "";
		$price = "";
		
		$message .= "<p class='message'>Your post has been successfully added! It is now pending!</p>";
		$message .= "<p class='message'><a href='posts.php?u=$log_username'>Click here</a> to go to your posts</p>";
	}

	
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add item</title>
	<link rel="shortcut icon" href="images/icon.png"/>
	<link href="css/register.css" type="text/css" rel="stylesheet">
</head>
<body>
<?php include_once("include/header.php"); ?>
<div id="registerForm">
<h1>Add Item</h1>
<?php
	echo $errors; 
	echo $message; 
?>
<form action="" method="post" enctype="multipart/form-data">
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
	<div>
		<label for="itemImage">Upload image:</label>
		<input type='file' name='itemImage'>
	</div>
	<input type="submit" value="Add" name="submit">
</form>
</div>
</body>
</html>