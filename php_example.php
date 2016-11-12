
<?php
$publisher = "919878668572272";
$sort = "relevance";  ## Sort by "relevance" or "date"
$radius = "50";  ## Distance from search location
$st = "";  ## Site type (default blank- can set to "jobsite" or "employer")
$jt = "";  ## Job type (default blank- can set to fulltime, parttime, contract, internship, or temporary)
$start_number = 0;  ## Start search results at this number (used for pagination)
$limit = "10";  ## Number of results per page
$fromage = "";  ## Number of days to search back (default blank = 30)
$highlght = "1";  ## Bold the keyword search terms in results (set to 0 for no bold)
$filter = "1";  ## Filters out duplicate results (set to 0 for no filter)
$latlong = "1";  ## If latlong=1, returns latitude and longitude information for each result
$co = "us";  ## Country to search jobs in
$chnl = "";  ## API Channel request.  Leave blank for none
$query = "web analytics";
$location = "55403";
$highlight = "1";
$userip = $_SERVER['REMOTE_ADDR'];  ## IP address of user
$useragent = $_SERVER['HTTP_USER_AGENT'];  ## User's browser type    

$params =
       array(
                'publisher' => $publisher,
                'q' => $query,
                'l' => $location,
                'sort' => $sort,
                'radius' => $radius,
                'st' => $st,
                'jt' => $jt,
                'start' => $start_number,
                'limit' => $limit,
                'fromage' => $fromage,
                'filter' => $filter,
                'latlong' => $latlong,
                'co' => $co,
                'chnl' => $chnl,
                'userip' => $userip,
                'useragent' => $useragent,
                 );


$url = 'http://api.indeed.com/ads/apisearch'.'?'.http_build_query($params,'', '&');


$xml = (simplexml_load_file($url));
$xmlobj = $xml;

foreach ($xmlobj->results->result as $job) {
   echo sprintf("%s<br>\n%s<br><br>\n\n", (string)$job->jobtitle,
(string)$job->snippet); }
?>