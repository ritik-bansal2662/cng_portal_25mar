<?php

// this api is used by:
// 1. lcv_status.js
// 2.

include '../../CNG_API/conn.php';

// to return a json value
header('Content-Type: application/json; charset=utf-8');

$response = array();

if(isset($_GET['station_id'])) {
    $station_id = $_GET['station_id'];
    $select_query = "SELECT Station_Address from luag_station_master where Station_Id='$station_id'";
    $result = mysqli_query($conn, $select_query);
    $num_rows = mysqli_num_rows($result);

    $temp_array = array();
    if($num_rows > 0) {
        while($row = $result-> fetch_assoc()) {
            $response['station_address'] = $row['Station_Address'];
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'No data found for selected station.';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'No Station Selected.';
}


echo json_encode($response)

?>

