<?php
session_start();
?>

<html>
	<head>
		<meta charset="utf-8">
		<title> Job feed from indeed </title>
		<!--<link rel="stylesheet.css" >-->
	</head>
	<body>
		<h1>Welcome</h1>
		
		<form action="indeed_v3.php" method="POST">
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
	</body>
</html>

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
	$hold = array("job" => $job, "location" -> $loc, "radius" => $r, "jobtype" => $jt, "startloop" => $start, "totalresults" => $total,"finishloop" => $finish);
	
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
	$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=$q&l=$l&radius=$r&st=&jt=$jt&start=&limit=$limit&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	$xml = simplexml_load_file($url);
	
	$html = "";
	//set up var to use from session
	//$amount = $_SESSION["hold"]["limit"];
	//$finish = $_SESSION["hold"]["finishloop"];


	//if you look closely I put the users post response look for the $variable sign
	//$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=$q&l=$l&sort=$s&radius=$r&st=&jt=&start=&limit=$amount&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	

	//this works as a default
	//$html = "";
	//$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=java&l=austin%2C+tx&sort=&radius=&st=&jt=&start=&limit=&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	//$xml = simplexml_load_file($url);

	for($i=0; $i < $limit;$i++){
		$title = $xml->results->result[$i]->jobtitle;
		$company = $xml->results->result[$i]->company;
		$latitude = $xml->results->result[$i]->latitude;
		$longitude = $xml->results->result[$i]->longitude;
		$snippet = $xml->results->result[$i]->snippet;
		
		$html .= "<p>title: $title <br> latitude: $latitude, longitude: $longitude</p> <br> <p>Company: $company</p> <br> <p> Description: $snippet</p>";
	}

	echo $html;
}

?>

