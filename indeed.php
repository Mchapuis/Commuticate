<?php
session_start();
$cd ~/public_html
$php -S localhost:8000
?>

<html>
	<head>
		<meta charset="utf-8">
		<title> Job feed from indeed </title>
		<!--<link rel="stylesheet.css" >-->
	</head>
	<body>
		<h1>Welcome</h1>
		
		<form action="indeed.php" method="POST">
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
				SORT
				<select name="sort">
				<!--full time is default selected-->
					<option selected value="relevance">relevance</option>
					<option value="date">date</option>
				</select><br>
				JOBS PER PAGE
				<select name="amount">
				<!--fullt time is default selected-->
				<option value="5">5</option>
				<option value="10">5</option>
				<option value="15">5</option>
				<option value="20">5</option>
				<option selected value="25">5</option>
				</selecte><br>
			</fieldset>
			<input type="subset" name="search" value="submit">
			</form>
	</body>
</html>

<?php
ini_set("display_errors","On");
//if search button is clicked
if(isset($_REQUEST['search'])){
	
	//check to see if the search button was hit
	//echo "the search button has been hit";
	
	//holds all of the HTML post Request
	$job = $_POST["job"];
	$loc = $_POST["location"];
	$r = (init)$_POST["radius"];
	$jt = $_POST["jobtype"];
	$sort = $_POST["sort"];
	
	//hold the users requested amount per page used
	$amount = (init)$_POST["amount"];
	
	//used to go throught all of the jobs and keep track of position in list of jobs
	$start = 0;
	$total = 0;
	//made finish equal amount because api only allowed max 25..so loop 25
	$finish = $amount;
	
	//hold all the information I need for later in the program
	$hold = array("job" => $job, "location" -> $loc, "jobtype" => $jt, "radius" => $r, "sort" => $sort, "amount" => $amount, "startloop" => $start, "totalresults" => $total,"finishloop" => $finish);
	
	//had to create session to cave info so I can research when user hits next page
	$_SESSION["hold"] = $hold;
	
	//for debugging
	#echo "display this many job: " . $finish . "<br>";
	
	//does job search
	getjobs();
}

//does job search
function getjobs(){
	//took information from session so I can use to start job search
	$q = $_SESSION["hold"]["job"];
	$l = $_SESSION["hold"]["location"];
	$jt = $_SESSION["hold"]["jobtype"];
	$r = $_SESSION["hold"]["radius"];
	$s = $_SESSION["hold"]["sort"];
	$amount = $_SESSION["hold"]["amount"];
	$start = $_SESSION["hold"]["startloop"];
	$total = $_SESSION["hold"]["totalresults"];
	$finish = $_SESSION["hold"]["finishloop"];
	
	//if you look closely I put the users post response look for the $variable sign
	$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=$q&l=$l&sort=$s&radius=$r&st=&jt=&start=&limit=$amount&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	
	//$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=$q&l=austin%2C+tx&sort=&radius=&st=&jt=&start=&limit=&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	
	//function is used to convert the well-formed XML document an object
	$xml = simplexml_load_file($url);
	
	//testing to see if object is there
	$query = $xml->query;
	$place = $xml->location;
	$totalresults = (int)$xml->totalresults;
	
	$_SESSION["hold"]["totalresults"] = $totalresults;
	
	//check if print ok
	$print = "yes";
	
	if( $totalresults ==0 ){
		echo "no results...<br>";
		$print = "no";
	}
	if($print == "yes"){
		//checks if less results than requested
		if($totalresults < $amount){
			//make sure we dint try to access jobs that don;t exist
			$_SESSION["hold"]["amount"] = abs($totalresults = $amount);
		}
		
		//print the jobs
		printjobs($xml);
	}		
}

//prints jobs
function printjobs($xml){
	//var used to hold dynamically created html
	$html = "";
	//set up var to use from session
	$amount = $_SESSION["hold"]["amount"];
	$finish = $_SESSION["hold"]["finishloop"];
	
	//loops through the xml and gathers all information on open jobs
	for($x=0; $x < $amount; $x++){
		$jobtitle = $xml->results->result[$x]->jobtitle;
		$adress= $xml->results->result[$x]->url;
		$company = $xml->results->result[$x]->company;
		$location = $xml->results->result[$x]->formattedLocation;
		$source = $xml->results->result[$x]->source;
		$date = $xml->results->result[$x]->date;
		$snippet = $xml->results->result[$x]->snippet;
		$time = $xml->results->result[$x]->formattedRelativeTime;
		
		
		//OUTPUT OF JOB
		$html = "<p>Job:$jobtitle</p><p>company: $company</p><p>location: $location</p><p>source: $source</p><p>DatePosted: $date</p><p>Description: $snippet</p><p>Last updated: $time</p><br>";
	
	}
	
	//after the loop I want to set end of loop to start of next loop
	$_SESSION['hold']['finishloop'] = $finish + $_SESSION['hold']['amount'];
	
	//loop end now = previous loop end plus the request amount of searches per page
	$_SESSION['hold']['finishloop'] = $finish + $_SESSION['hold']['amount'];
	
	echo "<h1>Results</h1>";
	
	//prints out all of the results
	echo $html;
	
	//see if the startloop is less than total amount
	if($_SESSION["hold"]["startloop"] < $_SESSION["hold"]["totalresults"]){
		//if so we have more jobs to show so create a next button
		echo "for more results hit the next button<br>";
		echo "<form action='indeed.php' method='POST'>";
		echo "<input type='submit' name= 'next' value='next'>";
		echo "</form>";
	}
}

//this shows the next page of the results on the same page
if(isset($_REQUEST['next'])){
	
	$q = $_SESSION['hold']['job'];
	$l = $_SESSION['hold']['location'];
	$jt = $_SESSION['hold']['jobtype'];
	$r = $_SESSION['hold']['radius'];
	$s = $_SESSION['hold']['sort'];
	$a = $_SESSION['hold']['amount'];
	$start = $_SESSION['hold']['startloop'];
	$t = $_SESSION['hold']['totalresults'];
	
	
	//this is not the same url as above
	$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=$q&l=$l&sort=$s&radius=$r&st=&jt=&start=$start&limit=$a&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	$xml = simplexml_load_file($url);
	
	//if start amount is less than the total we know that we done have to adjust
	if( $start + $a <= $t){
		//regular print
		printjobs($xml);
	}
	else{
		//since bigger, need to adjust in pages
		$_SESSION['hold']['amount'] = $t = $start;
		printjobs($xml);
	}
}

?>





























