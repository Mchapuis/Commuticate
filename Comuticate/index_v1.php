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
		<script src="https://js.arcgis.com/4.1/"></script>
		<script>
		require([
			"esri/Map",
			"esri/views/MapView",
			"dojo/domReady!"
		], function(Map, MapView){
			var map = new Map({
			basemap: "streets"
		});
		var view = new MapView({
			container: "viewDiv",  // Reference to the scene div created in step 5
			map: map,  // Reference to the map object created before the scene
			zoom: 4,  // Sets the zoom level based on level of detail (LOD)
			center: [15, 65]  // Sets the center point of view in lon/lat
			});
		});
		</script>
		<!--end For the map- aregis-->
	</head>
	<body>
		<h1>Welcome</h1>

		<form action="index_v1.php" method="POST">
			<fieldset>
				<legend>Search for a job</legend>
				Job : <input type="text" name="job" id="job"><br>
				location : <input type="text" name="location"><br>
				radius : <input type="number" name="radius" min="0"><br>
				JOB TYPE
				<select name="jobtype">
					<!--full time is defautl-->
					<option selected value ="fulltime">Full Time</option>
					<option value="partime">Part Time</option>
					<option value="contract">Contract</option>
					<option value="internship">Internship</option>
					<option value="temporary">Temporary</option>
				</select><br>
			</fieldset>
			<input type="submit" name="search" value="submit">
			</form>
			<!-- MAP Argis-->


			<div class="row rowMap viewDiv" id="viewDiv">

			</div>

			<div class="row paraDiv">
			  <form>
			     Your Home<br>
			    <input type="text" name="firstname"><br>
			     Occupational Field<br>
			    <input type="text" name="lastname">
			  </form>
			</div>
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
<?php
//ini_set("display_errors","On");
//if search button is clicked
//echo "before";
if(isset($_REQUEST['search'])){

	//check to see if the search button was hit
	//echo "the search button has been hit";

	//holds all of the HTML post Request
	$job = $_POST['job'];
	$loc = $_POST['location'];
	$r = $_POST['radius'];
	$jt = $_POST['jobtype'];

	//set the limit of jobs per page
	$limit = 25;
	//used to go throught all of the jobs and keep track of position in list of jobs
	$start = 0;
	$total = 0;
	//made finish equal amount because api only allowed max 25..so loop 25
	$finish = $limit;

	//hold all the information I need for later in the program
	$hold = array("job" => $job, "location" => $loc, "radius" => $r, "jobtype" => $jt, "startloop" => $start, "totalresults" => $total,"finishloop" => $finish);

	//had to create session to cave info so I can research when user hits next page
	$_SESSION["hold"] = $hold;

	//took information from session so I can use to start job search
	$q = $_SESSION["hold"]["job"];
	$l = $_SESSION["hold"]["location"];
	$r = $_SESSION["hold"]["radius"];
	$jt = $_SESSION["hold"]["jobtype"];
	$start = $_SESSION["hold"]["startloop"];
	$total = $_SESSION["hold"]["totalresults"];
	$finish = $_SESSION["hold"]["finishloop"];

	//trying to change this with inputs
	$html = "";
	//$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=java&l=austin%2C+tx&sort=&radius=&st=&jt=&start=&limit=&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";

	$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=$q&l=$l&radius=$r&st=&jt=$jt&start=&limit=$limit&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	$xml = simplexml_load_file($url);


	//this works as a default
	//$html = "";
	//$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=java&l=austin%2C+tx&sort=&radius=&st=&jt=&start=&limit=&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	//$xml = simplexml_load_file($url);

	for($i=0; $i < 5;$i++){
		$title = $xml->results->result[$i]->jobtitle;
		$company = $xml->results->result[$i]->company;
		$latitude = $xml->results->result[$i]->latitude;
		$longitude = $xml->results->result[$i]->longitude;
		$snippet = $xml->results->result[$i]->snippet;

		$html .= "<p>title: $title <br> latitude: $latitude, longitude: $longitude</p> <br> <p>Company: $company</p> <br> <p> Description: $snippet</p>";
	}

	//Print all the result together
	//need to separate each call to it's proper position
	echo $html;
}

?>
