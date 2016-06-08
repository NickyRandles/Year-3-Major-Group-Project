<?php 
include_once("include/checkLoginStatus.php");
include_once("include/header.php");
include_once("include/db_connect.php");
if($loggedIn != true && $admin != true){
	header("Location: http://localhost:8080/Project/index.php");
	exit();
}

$reports = "";

$sql = "SELECT * FROM reports";
$query = mysql_query($sql);
$numRows = mysql_num_rows($query);

if($numRows < 1){
	$reports = "<p>No reports about any users have been made</p>";
}
else{
	$reports = "<h1>Reports</h1>";
	$reports .= "<table>";
	$reports .= "<tr>";
	$reports .= "<th>Reporter</th>";
	$reports .= "<th>Reported</th>";
	$reports .= "<th>Reason</th>";
	$reports .= "<th>Explanation</th>";
	$reports .= "<th>Report Time</th>";
	$reports .= "<th>Options</th>";
	$reports .= "</tr>";
	
	while($row = mysql_fetch_array($query)){
		$id = $row["id"];
		$reporter = $row["reporter"];
		$reported = $row["reported"];
		$reason = $row["reason"];
		$explanation = $row["explanation"];
		$reportTime = $row["reportTime"];
		
		$reporterBanned = false;
		$reportedBanned = false;
		$sql2 = "SELECT * FROM users WHERE username = '$reporter' AND userType = 'b' LIMIT 1";
		$query2 = mysql_query($sql2);
		$numRows2 = mysql_num_rows($query2);
		if($numRows2 > 0){
			$reporterBanned = true;
		}
		$sql3 = "SELECT * FROM users WHERE username = '$reported' AND userType = 'b' LIMIT 1";
		$query3 = mysql_query($sql3);
		$numRows3 = mysql_num_rows($query3);
		if($numRows3 > 0){
			$reportedBanned = true;
		}
		
		$reports .= "<tr id='$id'>";
		$reports .= "<td><a href='user.php?u=$reporter'>$reporter</a></td>";
		$reports .= "<td><a href='user.php?u=$reported'>$reported</a></td>";
		$reports .= "<td>$reason</td>";
		$reports .= "<td id='explan'>$explanation</td>";
		$reports .= "<td>$reportTime</td>";
		$reports .= "<td>";
		if($reporterBanned == false){
			$reports .= "<span class ='$reporter'><button onclick='banToggle(\"ban\", \"$reporter\")'>Ban $reporter</button><br></span>";
		}
		else if($reporterBanned == true){
			$reports .= "<span class ='$reporter'><button onclick='banToggle(\"unban\", \"$reporter\")'>Unban $reporter</button><br></span>";
		}
		if($reportedBanned == false){
			$reports .= "<span class ='$reported'><button onclick='banToggle(\"ban\", \"$reported\")'>Ban $reported</button><br></span>";
		}
		else if($reportedBanned == true){
			$reports .= "<span class ='$reported'><button onclick='banToggle(\"unban\", \"$reported\")'>Unban $reported</button><br></span>";
		}		
		$reports .= "<span><button onclick='deleteReport(\"$id\")'>Delete report</button><br></span>";
		$reports .= "</td>";
		$reports .= "</tr>";
	}
	
	$reports .= "</table>";
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
	
	function banToggle(option, user){
		
		$.post("parsers/handleReports.php", {option: option, username: user}, function(data){
			if(data == "bannedSuccess"){
				$("." + user).html('<button onclick=\'banToggle("unban", "' + user + '")\'>Unban ' + user + '</button><br>');
			}
			else if(data == "unbannedSuccess"){
				$("." + user).html('<button onclick=\'banToggle("ban", "' + user + '")\'>Ban ' + user + '</button><br>');
			}
			else{
				alert(data);
			}
		});
	}
	
	function deleteReport(id){
		$.post("parsers/handleReports.php", {reportId: id}, function(data){
			if(data == "postDeleted"){
				$("#" + id).hide();
			}
		});
	}
</script>
<style>
h1{
	margin-top: 70px;
	text-align: center;
	color: #00BFFF;
}
table{
	width: 90%;
	margin: 0 auto;
	border-spacing: 25px;
	border-collapse: collapse;
	overflow: auto;
}
th, td{
	border: 1px solid #00BFFF;
	text-align: center;
	padding: 10px;
}

table button{
	padding: 5px;
	border-radius: 5px;
}

#explan{
	width: 40%;
}
table span{
	display: block;
	padding: 2px;
}
table a{
	text-decoration: none;
}
table a:hover{
	text-decoration: underline;
}
</style>
</head>
<body>
<?php echo $reports ?>
</body>
</html>











