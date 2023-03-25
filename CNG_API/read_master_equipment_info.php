<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cng_luag";

include "conn.php";
// $conn = new mysqli($servername, $username, $password, $dbname);

$response = array();
if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT Station_id,
    Station_type,
    mgsId,
    stationary_cascade_id,
    stationary_cascade_make,
    stationary_cascade_model,
    stationary_cascade_serial_number,
    stationary_cascade_installation_date,
    stationary_cascade_hydrotest_status_date,
    stationary_hydrotest_status,
    stationary_cascade_capacity,
    stationary_cascade_reorder_point,
    compressor_id,
    compressor_make,
    compressor_model,
    compressor_serial_number,
    compressor_type,
    dispenser_id,
    dispenser_make,
    dispenser_model,
    dispenser_type 
    FROM luag_station_equipment_master 
    WHERE Station_Id = ?");
    $stmt->bind_param("s", $id);
    $result = $stmt->execute();

    if ($result == TRUE) {
        $response['error'] = false;
        $response['message'] = "Retrieval Successful!";
        $stmt->store_result();
        $stmt->bind_result(
            $Station_id,
            $Station_type,
            $mgsId,
            $stationary_cascade_id,
            $stationary_cascade_make,
            $stationary_cascade_model,
            $stationary_cascade_serial_number,
            $stationary_cascade_installation_date,
            $stationary_cascade_hydrotest_status_date,
            $stationary_hydrotest_status,
            $stationary_cascade_capacity,
            $stationary_cascade_reorder_point,
            $compressor_id,
            $compressor_make,
            $compressor_model,
            $compressor_serial_number,
            $compressor_type,
            $dispenser_id,
            $dispenser_make,
            $dispenser_model,
            $dispenser_type
        );
        $stmt->fetch();
        $response['Station_id'] = $Station_id;
        $response['Station_type'] = $Station_type;
        $response['mgsId']=$mgsId;
        $response['stationary_cascade_id'] = $stationary_cascade_id;
        $response['stationary_cascade_make'] = $stationary_cascade_make;
        $response['stationary_cascade_model'] = $stationary_cascade_model;
        $response['stationary_cascade_serial_number'] = $stationary_cascade_serial_number;
        $response['stationary_cascade_installation_date'] = $stationary_cascade_installation_date;
        $response['stationary_cascade_hydrotest_status_date'] = $stationary_cascade_hydrotest_status_date;
        $response['stationary_hydrotest_status'] = $stationary_hydrotest_status;
        $response['stationary_cascade_capacity'] = $stationary_cascade_capacity;
        $response['stationary_cascade_reorder_point'] = $stationary_cascade_reorder_point;

        $response['compressor_id'] = $compressor_id;
        $response['compressor_make'] = $compressor_make;
        $response['compressor_model'] = $compressor_model;
        $response['compressor_serial_number'] = $compressor_serial_number;
        $response['compressor_type'] = $compressor_type;
        $response['dispenser_id'] = $dispenser_id;
        $response['dispenser_make'] = $dispenser_make;
        $response['dispenser_model'] = $dispenser_model;
        $response['dispenser_type'] = $dispenser_type;
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect id";
    }
} else {

    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}

echo json_encode($response);
