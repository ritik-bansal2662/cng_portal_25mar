<?php

// this api is used by:
// 1. register-station.js
// 2.

include '../../CNG_API/conn.php';

// to return a json value
header('Content-Type: application/json; charset=utf-8');

$response = array();

if(isset($_GET['station_id'])) {

    $station_id = $_GET['station_id'];

    $select_query = "SELECT stationary_cascade_id from luag_station_equipment_master where station_id = '$station_id'";
    $result = mysqli_query($conn, $select_query);
    $num_rows = mysqli_num_rows($result);

    // $temp_array = array();
    if($num_rows > 0) {
        $i=0;
        while($row = $result-> fetch_assoc()) {
            $response[$i] = $row['stationary_cascade_id'];
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'No Stationary Cascade Registered for selected Station.';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'No Station Selected';
}


echo json_encode($response)

?>

