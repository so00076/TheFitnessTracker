<!DOCTYPE HTML>

<html lang="en">
	<head>
	
	    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
	
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
		<script>
		var map;
		function initialize() {
		  // Create a simple map.
		  map = new google.maps.Map(document.getElementById('map-canvas'), {
			zoom: 14,
			center: {lat: 51.4, lng: 0.78}
		  });

		  // Load a GeoJSON from the same server as our demo.
		  map.data.loadGeoJson('map.json');
		}

		google.maps.event.addDomListener(window, 'load', initialize);
	</script>

	</head>

	
	<div id='map-canvas'>
	</div>
	

	
</html>