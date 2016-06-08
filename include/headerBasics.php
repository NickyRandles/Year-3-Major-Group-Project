<!DOCTYPE html>
<html>
<head>
	<link href="css/header.css" type="text/css" rel="stylesheet">
	<script src="jquery/jquery-1.11.2.min.js"></script>
	<script src="jquery/jquery-ui.min.js"></script>
	<link href="jquery/jquery-ui.min.css" type="text/css" rel="stylesheet">
	<script>
		function getSearchItem(value){
			var type = document.getElementById("type").value;
			if(value != ""){
				$("#searchSuggestions").html('');
			}
			$.post("parsers/getSearchItem.php", {searchItem:value, searchType: type}, function(data){
				$("#searchSuggestions").html(data);
					
				$("#searchSuggestions li").click(function(){
					var result = $(this).text();
					$("#search").val(result);
					$("#searchSuggestions").html('');
				});
			});
		}	
	</script>
</head>
<nav>
	<ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="search.php">Search</a></li>
		<li><a href="categories.php">Categories</a></li>
		<li><a href="shops.php">Sales on</a>
			<ul>
				<li><a href="shops.php#aldi">at Adli</a></li>
				<li><a href="http://www.lidl.ie/en/index.htm" target="_blank">at Lidl</a></li>
				<li><a href="http://tesco.ie/" target="_blank">at Tesco</a></li>
				<li><a href="shops.php#dunnes">at Dunnes</a></li>
			</ul>
		</li>	
		<li><a href="find.php">Find shops</a></li>
	</ul>
</nav>
<?php
$url = $_SERVER['REQUEST_URI'];
if($url == "/Project/" || $url == "/Project/index.php" || substr($url, 0, 19) == "/Project/search.php"){
	echo "<form action='search.php' method='get'>";
	echo "<label for='searchBox'>Search</label>";
	echo "<input type='text' name='search' id='search' placeholder='Enter Query'  autocomplete='off' onkeyup='getSearchItem(this.value)'>";
	echo "<select name='type' id='type'>";
	echo "<option value='item'>Item</option>";
	echo "<option value='person'>Person</option>";
	echo "<option value='group'>Group</option>";
	echo "</select>";
	echo "<div id='searchSuggestions'>";
	echo "</div>";
	echo "<input type='submit' value='Search'>";
	echo "</form>";
}
?>


</body>
</html>