<?php

// this api is used by:
// 1. register-station.js
// 2.

include '../../CNG_API/conn.php';

// to return a json value
header('Content-Type: application/json; charset=utf-8');

$response = array();

if(isset($_GET['apicall'])) {

    $instrument_id='';

    switch($_GET['apicall']) {
        case 'temperature_gauge':
            $instrument_id = 'temperature_gauge_id';
            break;
        case 'low_pressure_gauge':
            $instrument_id = 'low_pressure_gauge_id';
            break;
        case 'medium_pressure_gauge':
            $instrument_id = 'medium_pressure_gauge_id';
            break;
        case 'high_pressure_gauge':
            $instrument_id = 'high_pressure_gauge_id';
            break;
        case 'mass_flow_meter':
            $instrument_id = 'mass_flow_meter_id';
            break;
        default :
            $instrument_id = 'temperature_gauge_id';
            break;
    }

    $select_query = "SELECT $instrument_id from luag_station_instrument_master";
    $result = mysqli_query($conn, $select_query);
    $num_rows = mysqli_num_rows($result);

    $temp_array = array();
    if($num_rows > 0) {
        while($row = $result-> fetch_assoc()) {
            $response[$row[$instrument_id]] = $row[$instrument_id];
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

