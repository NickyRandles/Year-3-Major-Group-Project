 <!DOCTYPE html>
<html>
<head>
	<title>Shop and Save</title>
		
	<link rel="shortcut icon" href="images/icon.png"/>
	<link href="css/header.css" type="text/css" rel="stylesheet">
	<link href="css/find.css" type="text/css" rel="stylesheet">
		<script src="jquery/jquery-1.11.2.min.js"></script>
	<script src="jquery/jquery-ui.min.js"></script>
	<link href="jquery/jquery-ui.min.css" type="text/css" rel="stylesheet">
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false">
    </script>
	<script type="text/javascript">
			$("document").ready(function(){
			$("#tabs").tabs();
			initialise();
		})

		
		var map;
		var services;
		var currentLocation;
		var geocoder;
		var distance;
		var totalDistance;
		var store;
		
		function initialise(){
			currentLocation = new google.maps.LatLng(53.344103999999990000, -6.267493699999932000);
			
			var mapOptions = {
				center: currentLocation,
				zoom: 10, 
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			
	
			map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			var marker = new google.maps.Marker({
			position: currentLocation,
			map: map
		});
			services = new google.maps.places.PlacesService(map);
			geocoder = new google.maps.Geocoder();
		}
		
		function performSearch(){
			//getting value from store input
			var input = document.getElementById("store").value;
			//setting store value to input
			store = input;
			//getting value from distance input
			distance = document.getElementById("distance").value;
			//multiplying it by 1000 to turn metres in kilometres
			totalDistance = distance * 1000;
			
			var request = {
				location: currentLocation,
				radius: totalDistance,
				name: store
			};
			
			services.nearbySearch(request, handleResults)
		}
		
		function handleResults(results, status){
			console.log(results);
			if (status == google.maps.places.PlacesServiceStatus.OK) {
				for (var i = 0; i < results.length; i++) {
					var marker = new google.maps.Marker({
						position: results[i].geometry.location,
						map: map,
						icon: 'images/' + store + '_icon.png'
					});
				
				}
			}
		}
		
		function codeAddress() {
			var address = document.getElementById("address").value;
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					currentLocation = results[0].geometry.location;
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location
					});
				}
				else {
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
		}
		
		function storeEntry(){
			
		}
		
		
	</script>
</head>
<body>
<?php include_once("include/header.php"); ?>
<div id = "tabs">
	<ul>
		<li><a href="#tab1">Map</a>	</li>
		<!--<li><a href="#tab2">Directions</a></li>-->
	</ul>
	
	<div id="tab1">
		<div id="controls">
			<div id ="control1">
				<label for="address">Enter you address:</label>
				<input type="text" name="address" id="address">
				<input type = "button" onclick="codeAddress()">	
			</div>
			<div id ="control2">
				<label for="store">Choose a store:</label>
				<select id="store">
					<option value="">Select</option>
					<option value="Tesco">Tesco</option>
					<option value="ALDI">ALDI</option>
					<option value="LIDL">LIDL</option>
				</select>
			</div>
			<div id ="control3">
				<label for="distance">Max distance from you address (in KMs):</label>
				<input type="number" name="distance" id="distance" min="1" max="100" value="1">
			</div>
			<div id ="control4">
				<input type = "button" value = "Enter" onclick="performSearch()">	
			</div>
		</div>
		<div id = "map-canvas">
		</div>
	</div>
	<!--
	<div id="tab2">
		<div id = "directions-panel">
		</div>
	</div>
	-->
</div>
<footer>
	<hr>
	&copy; Shop and Save 2014
</footer>
<script>
		$('#address').keyup(function(e) {
			if (e.keyCode == 13) {
				codeAddress();
			}
		});
	
	</script>
</body>
</html>