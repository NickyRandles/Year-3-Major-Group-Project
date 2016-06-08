$("document").ready(function(){
	$("#tabs").tabs();
});

var map;
var directionDisplay;
var directionsService = new google.maps.DirectionsService();
var myLatlng = new google.maps.LatLng(53.507276, -6.462870);

function initialise()
{
	directionDisplay = new google.maps.DirectionsRenderer();

	var mapOptions = {
		center: myLatlng,
		zoom: 10
	};

	map = new google.maps.Map(document.getElementById("map-canvas"),
	mapOptions);

	var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title: 'Ratoath Community Centre',
		icon: 'images/rcc.jpg'
	});

	directionDisplay.setMap(map);
	directionDisplay.setPanel(document.getElementById('directions-panel'));

	var control = document.getElementById('control');
	control.style.display = 'block';
	map.controls[google.maps.ControlPosition.TOP].push(control);
}

function calcRoute(){
	var start = $("#input").val();
	var end = myLatlng;
	var request = {
		origin: start,
		destination: end,
		travelMode: google.maps.DirectionsTravelMode.DRIVING
	};
	directionsService.route(request, function(response, status){
		if(status == google.maps.DirectionsStatus.OK){
			directionDisplay.setDirections(response);
		}
	});
}	
 
google.maps.event.addDomListener(window, 'load', initialise);