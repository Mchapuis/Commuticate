<?php
//ini_set("display_errors","On");
//if search button is clicked
//echo "before";
 //if (isset($_REQUEST['search'])) {
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

    $url = "http://api.indeed.com/ads/apisearch?publisher=919878668572272&format=json&q=$q&l=$l&radius=$r&st=&jt=$jt&start=&limit=$limit&fromage=&filter=&latlong=1&co=us&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29&v=2";
    echo file_get_contents($url);

?>
