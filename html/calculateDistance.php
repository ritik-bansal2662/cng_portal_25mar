<?php
include('db.php');
error_reporting(E_ERROR | E_PARSE);
if (isset($_POST['latlngDBS'])) {
    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
    $dbsid = $_POST['dbsid'];
    $mgsid = $_POST['mgsid'];
    // $latlngMGS=$_POST['latlngMGS'];
    $latlngDBS = $_POST['latlngDBS'];

    $split_latlngDBS = explode(",", $latlngDBS);
    $lat1 = $split_latlngDBS[0];
    if (is_null($split_latlngDBS[1])) {
        $output = 'DBS lat lng is empty';
    } else {
        $lon1 = $split_latlngDBS[1];
    }

    $output = '';
    $query = "SELECT Latitude_Longitude FROM luag_station_master WHERE Station_Id = '$mgsid'";
    $result = mysqli_query($con, $query);
    $latlngMGS = '';
    if($result) {
    while ($row = mysqli_fetch_array($result)) {
        $latlngMGS = $row["Latitude_Longitude"];
    }
    $split_latlngMGS = explode(",", $latlngMGS);
    
    $lat2 = $split_latlngMGS[0];
    if (is_null($split_latlngMGS[1])) {
        $output .= 'MGS lat lng is empty';
    } else {
        $lon2 = $split_latlngMGS[1];
        $output .= distance($lat1, $lon1, $lat2, $lon2, "K");
    }
    }else {
        $output = "invalid query";
}

    // print($output);
    echo $output;
    mysqli_close($con);
}
