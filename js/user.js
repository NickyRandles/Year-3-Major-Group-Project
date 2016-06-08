$(document).ready(function(){
	$("#tabs").tabs();
	$("#report").hide();
	$("#reportButton").click(function(){
		$("#report").toggle("blind");
	});
	
	$("#message").hide();
	$("#messageButton").click(function(){
		$("#messageButton").hide();
		$("#message").toggle("blind");
	})
});

function friendToggle(type, user, elem){
	var conf = confirm("Press OK to confirm the '" + type + "' action on user " + user);
	if(conf != true){
		return false;
	}

	var element = document.getElementById(elem);
	element.innerHTML = "<img src='images/ajax.gif' alt='loading'>";
	var ajax = ajaxObj("POST", "parsers/friendSystem.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friendSuccess"){
				element.innerHTML = 'OK Friend Request Sent';
			}
			else if(ajax.responseText == "unfriendSuccess"){
				element.innerHTML = '<button onclick="friendToggle(\'friend\',\'" + user + "\',\'friendButton\')">Request As Friend</button>';
			} 
			else {
				alert(ajax.responseText);
				element.innerHTML = 'Try again later';
			}
		}
	}
	ajax.send("type=" + type + "&user=" + user);
}

function blockToggle(type, user, elem){
	var conf = confirm("Press OK to confirm the '" + type + "' action on user " + user);
	if(conf != true){
		return false;
	}

	var element = document.getElementById(elem);
	element.innerHTML = "<img src='images/ajax.gif' alt='loading'>";

	var ajax = ajaxObj("POST", "parsers/blockSystem.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax) == true){
			if(ajax.responseText == "blockSuccess"){
				element.innerHTML = "<button onclick=\"blockToggle('unblock', '" + user + "', 'blockButton')\">Unblock</button>";
			}
			else if(ajax.responseText == "unblockSuccess"){
				element.innerHTML = "<button onclick=\"blockToggle('block', '" + user + "', 'blockButton')\">Block</button>";
			}
			else{
				alert(ajax.responseText);
				element.innerHTML = "Please try again later ";
			}
		}
	}
	ajax.send("type=" + type + "&user=" + user)
	
}

function banToggle(option, user){
	
	$.post("parsers/handleReports.php", {option: option, username: user}, function(data){
		if(data == "bannedSuccess"){
			$("#banButton").html('<button onclick=\'banToggle("unban", "' + user + '")\'>Unban ' + user + '</button><br>');
		}
		else if(data == "unbannedSuccess"){
			$("#banButton").html('<button onclick=\'banToggle("ban", "' + user + '")\'>Ban ' + user + '</button><br>');
		}
		else{
			alert(data);
		}
	});
}

function sendReport(reporter, reported){
	var reason = $("#reason").val();
	var explanation = $("#explanation").val();
	$("#reportIssues").html("");
	if(reason == "" || explanation == ""){
		if(reason == ""){
			$("#reportIssues").append("<p><img src='images/warning.png' alt='Warning'>Please select a reason</p>");
		}
		if(explanation == ""){
			$("#reportIssues").append("<p><img src='images/warning.png' alt='Warning'>Please write a  explanation</p>");
		}
		return false;
	}
	
	$.post("parsers/reportSystem.php", {reporter: reporter, reported: reported, reason: reason, explanation: explanation}, function(data){
		if(data == "reportedSuccess"){
			$("#reportOperation").html("You have successfully reported " + reported);
		}
		else if(data == "alreadyReported"){
			$("#reportOperation").html("You have already reported " + reported);
		}
	});
}

function sendMessage(log_username, username){
	var message = $("#messageInput").val();
	
	$.post("parsers/messageSystem.php", {log_username: log_username, username: username, message: message}, function(data){
		if(data == "success"){
			$("#sendOperation").html("Your message has been send!");
		}
		else{
			alert(data);
		}
	});
}