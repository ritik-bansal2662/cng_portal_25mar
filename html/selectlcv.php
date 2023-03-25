<?php
include('db.php');
if (isset($_POST["lcvid"])) {
    $output = '';
    $query = "SELECT  lcv_status FROM `reg_lcv` WHERE `Lcv_Num`='" . $_POST["lcvid"] . "'";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $output .= $row["lcv_status"];
    }
    echo $output;
}
if (isset($_POST["dbsid"])) {
    $return_arr = array();
    $query = "SELECT Station_Id,mgsId,Latitude_Longitude FROM `luag_station_master` WHERE Station_Id = '" . $_POST["dbsid"] . "'";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($result)) {
        $mgsId = $row["mgsId"];
        $Latitude_Longitude = $row["Latitude_Longitude"];
        $return_arr[] = array(
            "mgsId" => $mgsId,
            "Latitude_Longitude" => $Latitude_Longitude
        );
    }
    echo json_encode($return_arr);
    // echo $output;
}
if (isset($_POST["mgsid"])) {
    $return_arr = array();
    $query = "SELECT Station_Id,mgsId,Latitude_Longitude FROM `luag_station_master` WHERE mgsId = '" . $_POST["mgsid"] . "'";
    $result = mysqli_query($con, $query);
    $output = "<option value='NA'>Select DBS id</option>";
    while ($row = mysqli_fetch_array($result)) {
        $output .= "<option value='".$row['Station_Id']."'>".$row['Station_Id']."</option>";
    }
    echo $output;
    // echo $output;
}
if (isset($_POST['latlngDBS'])) {
    $dbsid = $_POST['dbsid'];
    $mgsid = $_POST['mgsid'];
    $latlngDBS = $_POST['latlngDBS'];
    $split_latlngDBS = explode(",", $latlngDBS);
    $lat1 = $split_latlngDBS[0];
    $lon1 = $split_latlngDBS[1];

    $output = '';
    $query = "SELECT Latitude_Longitude FROM `luag_station_master` WHERE mgsId = '" . $_POST["mgsid"] . "'";
    $result = mysqli_query($con, $query);
    $latlngMGS = '';
    while ($row = mysqli_fetch_array($result)) {
        $latlngMGS = $row["Latitude_Longitude"];
    }

    $split_latlngMGS = explode(",", $latlngMGS);
    $lat2 = $split_latlngMGS[0];
    $lon2 = $split_latlngMGS[1];

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
    $output = distance($lat1, $lon1, $lat2, $lon2, "K");
    // print($output);
    echo $output;
    mysqli_close($con);
}
