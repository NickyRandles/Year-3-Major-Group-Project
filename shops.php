<?php
$name = "";
$frame = "";
$info = "";
if(isset($_GET["name"])){
	$name = $_GET["name"];
}

$info .= "<div id='accordion'>";
$info .= "<h3>Aldi</h3>";
$info .= "<div>";
$info .= "<iframe src='https://www.aldi.ie/' width='100%' height='1000'></iframe>";
$info .= "</div>";
$info .= "<h3>Lidl</h3>";
$info .= "<div>";
$info .= "<p>Embedded site not available.</p>";
$info .= "<a href='http://www.lidl.ie/en/index.htm' target='_blank'>Click to go to Lidl.ie</a>";
$info .= "</div>";
$info .= "<h3>Tesco</h3>";
$info .= "<div>";
$info .= "<p>Embedded site not available.</p>";
$info .= "<a href='http://tesco.ie/' target='_blank'>Click to go to Tesco.ie</a>";
$info .= "</div>";
$info .= "<h3>Dunnes</h3>";
$info .= "<div>";
$info .= "<iframe src='http://www.dunnesstores.com/' width='100%' height='1000'></iframe>";
$info .= "</div>";
$info .= "</div>";

?>
<?php include_once("include/header.php")?>
<!DOCTYPE html>
<html>
<head>
	<style>
		#accordion{
			margin-top: 80px;
		}
		#accordion a{
			color: blue;
			text-decoration: none;
		}
		#accordion a:hover{
			text-decoration: underline;
		}
	</style>
	<script>
		$(document).ready(function(){
			var name = "<?php echo $name ?>";
			if(name == "aldi"){
				$("#accordion").accordion({active: 0});
			}
			else if(name == "lidl"){
				$("#accordion").accordion({active: 1});
			}
			else if(name == "tesco"){
				$("#accordion").accordion({active: 2});
			}
			else if(name == "dunnes"){
				$("#accordion").accordion({active: 3});
			}
			else{
				$("#accordion").accordion({active: 0});
			}
		});
	</script>
</head>	
<body>
<?php 
echo $frame;
echo $info;
?>
</body>
</html>