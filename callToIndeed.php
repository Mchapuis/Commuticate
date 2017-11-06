<?php 
session_start();

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

// 1- GET INPUT FROM USER

if(isset ($_POST['jobInput']))
{
	$jobInput = $_POST['jobInput'];
	//echo $job;
}else
{
	$jobInput = "developer";
}

if(isset ($_POST['jobtype']))
{
	$jobType = $_POST['jobtype'];
	//echo $jt;
}else{
	$jobType = "contract";
}

if(isset ($_POST['autocomplete']))
{
	$garbage = $_POST['autocomplete'];
	//echo $garbage;
}else{
	$garbage = "nothing";
}

if(isset ($_POST['country']))
{
	$country = $_POST['country'];
	//echo $co;
}else{
	$country = "us";
}
if(isset ($_POST['subdivision']))
{
	$subdivision = $_POST['subdivision'];
	//echo $subdivision;
}
else{
	$subdivision = "tx";

}

if(isset ($_POST['city']))
{
	$city = $_POST['city'];
	//echo $city;
}
else{
	$city = "Austin";
}

//set the limit of jobs per page
$limit = 25;
$total = 0;
//used to go throught all of the jobs and keep track of position in list of jobs
$start = 0;
$finish = 25;

//hold all the information I need for later in the program "radius" => $r,
$hold = array("jobInput" => $jobInput, "jobtype" => $jobType, "startloop" => $start, "totalresults" => $total,"finishloop" => $finish, "city"=> $city, "country"=> $country, "subdivision"=>$subdivision,  "startloop" => $start, "finishloop" => $finish, "limit"=> $limit);

//had to create session to cave info so I can research when user hits next page
$_SESSION["hold"] = $hold;

// make API call to indeed for the first time
getjobs();

// 2 - FIRST CALL TO INDEED
function getjobs(){
	
	//took information from session so I can use to start job search
	$q = $_SESSION["hold"]["jobInput"];
	$jt = $_SESSION["hold"]["jobtype"];
	$start = $_SESSION["hold"]["startloop"];
	$totalResults = $_SESSION["hold"]["totalresults"];
	$finish = $_SESSION["hold"]["finishloop"];
	$c = $_SESSION["hold"]["country"];
	$ci = $_SESSION["hold"]["city"];
	$sub = $_SESSION["hold"]["subdivision"];
	$limit = $_SESSION["hold"]["limit"];

	$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&format=json&q=$q&st=&l=$ci%2C+$sub&co=$c&jt=$jt&start=$start&limit=$finish&fromage=&filter=&latlong=1&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
	
	// 3- CHECK TOTAL RESULTS
	$stringURL = file_get_contents($url);
	$jsonDecoded = json_decode($stringURL, TRUE);
	
	// get the total number of jobs back
	$checkResult = checkTotalResults($jsonDecoded);
	
	// if not 0, get all jobs
	if($checkResult != 0 )
	{
		//$allResultsObj = 
		getAllJobsInfo($jsonDecoded, $checkResult);
		
		//echo $test['totalResults'];// THIS WORKS!!!! R majuscule tabernacle
		//$allResultsObj = json_encode($allResultsObj);
		//echo ("YYYYYYAYYYYYYYY");
		//echo $allResultsObj;
	}
	else
	{
		echo ("NO JOBS FOUND!!!!");
	}

}

// pass encoded json
function checkTotalResults($url)
{

	$totalResults = (int)$url['totalResults'];
	$_SESSION['hold']['totalresults'] = $totalResults;
	$limit = $_SESSION["hold"]["limit"];
	// variable to check 
	$bMoreJob = "yes";
	
	// if no results
	if($totalResults == 0)
	{
		echo "No Results found!<br>";
		$bMoreJob = "no";
	}
	
	// So we don't ask for jobs that doesn't exist
	if($bMoreJob == "yes")
	{
		if($totalResults <= $limit)
		{
		$totalResults = $limit;
		$_SESSION["hold"]["totalresults"] = $totalResults;
		}
		return $totalResults;
	}
	else if($bMoreJob == "no")
	{
		return 0;
	}
}

function checkIfCanLoop($finishLoop, $tResults)
{
	// is finish bigger than totalResults?
	// yes -> return totalResults
	if($finishLoop > $tResults)
	{
		echo ("Bigger than total");
		return 0;
	}
	
	// is finish equal to totalResults?
	if($finishLoop == $tResults)
	{
		echo ("Equal than total");
		return -1;
	}
	return 2;
}

// pass decoded json and totalResults from the first call
function getAllJobsInfo($jsonDecoded, $totalResults)
{
	//$totalResults = 150;
	// To gather all results in one object
	$allResultsObj = $jsonDecoded;	
	
	// Make sure the variables are set
	$q = $_SESSION["hold"]["jobInput"];
	$jt = $_SESSION["hold"]["jobtype"];
	$c = $_SESSION["hold"]["country"];
	$ci = $_SESSION["hold"]["city"];
	$sub = $_SESSION["hold"]["subdivision"];
	$finish = $_SESSION["hold"]["finishloop"];
	// get the limit for each call to indeed
	$limit = $_SESSION["hold"]["limit"];
	
	//Since the first call has been done, continue with the second iteration
	// set the start of next loop to the end of first
	$_SESSION['hold']['startloop'] = $limit;
	$start = $_SESSION['hold']['startloop'];
	
	//Now the new finish is added the limit
	$finish = ($finish + $limit);
	
	// variable to check if we can loop again or not...
	$canContinue = 2;

	$canContinue = checkIfCanLoop($finish, $totalResults);

	while($canContinue == 2){
		echo("ANOTHER CAAALLLL");
		$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&format=json&q=$q&st=&l=$ci%2C+$sub&co=$c&jt=$jt&start=$start&limit=$finish&fromage=&filter=&latlong=1&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
		
		// decode the url
		$stringURL = file_get_contents($url);
		//$stringURL = utf8_decode($stringURL);
		$elements = json_decode($stringURL, TRUE);
		
		// add it to the final array/object
		if($elements != NULL)
		{
			array_push($allResultsObj, $elements);
		}
		
		// setup the new loop
		$start = $finish;
		$finish = ($finish + $limit);
		
		// check if numbers are ok/valid, return -1 if false
		$canContinue = checkIfCanLoop($finish, $totalResults);
	}
	//echo $test['totalResults'];// THIS WORKS!!!! R majuscule tabernacle
	$allResultsObj = json_encode($allResultsObj);
	
	//return $allResultsObj;
	echo $allResultsObj;
		
		
}

?>

