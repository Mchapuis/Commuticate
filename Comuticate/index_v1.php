<?php
session_start();
?>
<!--DISPLAY FOR INPUT OF USER -->
<html>
	<head>
		<!--For the map- aregis-->
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Communitcate</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.3/foundation.css">
		<link rel="stylesheet" href="./style/style.css">
		<link rel="stylesheet" href="https://js.arcgis.com/4.1/esri/css/main.css">
		<!--end For the map- aregis-->
	</head>
	<body>

		<form class="rowOptions row removeMarginPadding removeMaxWidth"action="index_v1.php" method="POST">
<div class="column medium-4">
	<h1>Welcome</h1>
	<p>Visualizing your job search on Indeed.com with Arcgis API</p>
</div>
<div class="column medium-4">
			<fieldset>
				<legend>Search for a job</legend>
				Job : <input class="input"  type="text" name="job" id="job">
				location : <input  class="input" type="text" name="location"><br>
				radius : <input class="input"  type="number" name="radius" min="0">
				</div>
				JOB TYPE
				<div class="column medium-4">
				<select class="input" name="jobtype">
					<!--full time is defautl-->
						<option selected value ="fulltime">Full Time</option>
						<option value="partime">Part Time</option>
						<option value="contract">Contract</option>
						<option value="internship">Internship</option>
						<option value="temporary">Temporary</option>
				</select><br>
			</fieldset>
			<input type="submit" name="search" value="submit" id="submitButton">
			</div>
			</form>
			<!-- MAP Argis-->


			<div class="row rowMap viewDiv" id="viewDiv">

			</div>

			<div class="row rowInfo">
			</div>

			<script src="https://js.arcgis.com/4.1/"></script>
			<script>

			var posX = 0;
			var posY  =0;

			require([
				"esri/Map",
				"esri/views/MapView",
				"dojo/on",
				"dojo/domReady!"
			], function(Map, MapView, on){
				var map = new Map({
				basemap: "streets"
			});
			var view = new MapView({
				container: "viewDiv",  // Reference to the scene div created in step 5
				map: map,  // Reference to the map object created before the scene
				zoom: 4,  // Sets the zoom level based on level of detail (LOD)
				center: [15, 65]  // Sets the center point of view in lon/lat
				});

				on(submitButton, "click", function(event){
					event.preventDefault()
					console.log("In recenter");

				 view.goTo([posX,posY]);
				 $.ajax('http://localhost/commuticate/Commuticate/Comuticate/dunno.php',{
					 method: 'post',
					 success: function(data){
						 console.log(data);
					 }
				 });
});

			});

			function rexy(x, y){
				posX = x;
				posY = y;
			}

			</script>
	</body>
</html>
<!-- END OF DISPLAY FOR INPUT OF USER -->


 <!-- js libraries -->


 <!-- MAP arcgis -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/foundation/6.2.4/foundation.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.4/plugins/foundation.core.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.4/plugins/foundation.util.mediaQuery.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.4/plugins/foundation.drilldown.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.4/plugins/foundation.dropdownMenu.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.4/plugins/foundation.util.keyboard.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.4/plugins/foundation.util.motion.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.4/plugins/foundation.util.nest.js"></script>
<script>
   $(document).ready(function() {
      $(document).foundation();
   })
</script>

<!-- END MAP Argis-->

<!-- PROCESS OF INPUT -->
