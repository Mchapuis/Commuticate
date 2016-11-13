<?php
//ini_set("display_errors","On");
//if search button is clicked
//echo "before";
// if (isset($_REQUEST['search'])) {
    //check to see if the search button was hit
    //echo "the search button has been hit";

    //holds all of the HTML post Request
    $job = $_POST['job'];
    $loc = $_POST['location'];
    $r = $_POST['radius'];
    $jt = $_POST['jobtype'];

    printf('ca function');

    //set the limit of jobs per page
    $limit = 25;
    //used to go throught all of the jobs and keep track of position in list of jobs
    $start = 0;
    $total = 0;
    //made finish equal amount because api only allowed max 25..so loop 25
    $finish = $limit;

    //hold all the information I need for later in the program
    $hold = array('job' => $job, 'location' => $loc, 'radius' => $r, 'jobtype' => $jt, 'startloop' => $start, 'totalresults' => $total, 'finishloop' => $finish);

    //had to create session to cave info so I can research when user hits next page
    $_SESSION['hold'] = $hold;

    //took information from session so I can use to start job search
    $q = $_SESSION['hold']['job'];
    $l = $_SESSION['hold']['location'];
    $r = $_SESSION['hold']['radius'];
    $jt = $_SESSION['hold']['jobtype'];
    $start = $_SESSION['hold']['startloop'];
    $total = $_SESSION['hold']['totalresults'];
    $finish = $_SESSION['hold']['finishloop'];

    //trying to change this with inputs
    $html = '';
    //$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=java&l=austin%2C+tx&sort=&radius=&st=&jt=&start=&limit=&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";

    $url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=$q&l=$l&radius=$r&st=&jt=$jt&start=&limit=$limit&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
    $xml = simplexml_load_file($url);

    //this works as a default
    //$html = "";
    //$url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&q=java&l=austin%2C+tx&sort=&radius=&st=&jt=&start=&limit=&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
    //$xml = simplexml_load_file($url);

        $lim;

    foreach ($xml as $results) {
        $lim = $results->count();
    }

    printf($lim);

    for ($i = 0; $i < $lim; ++$i) {
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
// }

?>
