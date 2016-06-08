<?php
include_once("include/header.php");
?>
<!DOCTYPE html>
<html>
<head>
	<style>
		h1{
			text-align: center;
			margin-top: 100px;
			font-family: Arial, Helvetica, sans-serif;
			color: #00BFFF;
		}
		#form{
			width: 40%;
			margin-left: 37.5%;
		}
		
		select[name="category"], select[name="subcategory"]{
			height: 150px;
			border: 2px  solid #00BFFF;
		}
		
		#form input[type="submit"]{
			background: url("images/searchIcon1.png") no-repeat;
			height: 100px;
			width: 100px;
			margin-left: 5%;
			border: 2px  solid #00BFFF
		}
	</style>
	<script type="text/javascript">
		function populate(){
			var category = document.getElementById("category").value;
			var subcategory = document.getElementById("subcategory");
			subcategory.innerHTML = "";
			
			if(category == "choose"){
				var optionArray = ["|Choose subcategory", "|Choose category first"];
			}			
			else if(category == "dairy"){
				var optionArray = ["|Choose subcategory", "semi-skimmed|Semi-skimmed", "full-fat|Full-fat", "cheese|Cheese"];
			}
			else if(category == "fruit"){
				var optionArray = ["|Choose subcategory", "bananas|Bananas", "apples|Apples"];
			}

			for(var option in optionArray){
				var values = optionArray[option].split("|");
				var newOption = document.createElement("option");
				newOption.value = values[0];
				newOption.innerHTML = values[1];
				subcategory.options.add(newOption);
			}
		}
	</script>
</head>
<body>
<h1>Please select a category and subcategory</h1>
<form action="search.php" method="get" id="form">
	<select name="category" id="category" onchange="populate()">
		<option value="choose">Choose Category</option>
		<option value="dairy">Dairy</option>
		<option value="fruit">Fruit</option>	
	</select>
	<select name="subcategory" id="subcategory">	
		<option value="">Choose subcategory</option>
		<option value="">Choose category first</option>
	</select>	
	<input type="submit" value="&nbsp;">
</form>
</body>
</html>