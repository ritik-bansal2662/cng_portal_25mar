<?php

// this api is used by:
// 1. register-station.js
// 2.

include '../../CNG_API/conn.php';

// to return a json value
header('Content-Type: application/json; charset=utf-8');

$response = array();

if(isset($_GET['apicall'])) {

    $equipment_id='';

    switch($_GET['apicall']) {
        case 'stationary_cascade':
            $equipment_id = 'stationary_cascade_id';
            break;
        case 'compressor':
            $equipment_id = 'compressor_id';
            break;
        case 'dispenser':
            $equipment_id = 'dispenser_id';
            break;
        default :
            $equipment_id = 'stationary_cascade_id';
            break;
    }

    $select_query = "SELECT $equipment_id from luag_station_equipment_master";
    $result = mysqli_query($conn, $select_query);
    $num_rows = mysqli_num_rows($result);

    $temp_array = array();
    if($num_rows > 0) {
        while($row = $result-> fetch_assoc()) {
            $response[$row[$equipment_id]] = $row[$equipment_id];
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'No data found for selected equipment.';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'No API called.';
}


echo json_encode($response)

?>

