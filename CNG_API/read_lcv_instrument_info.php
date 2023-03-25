<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "cng_luag";

include "conn.php";

// $conn = new mysqli($servername, $username, $password, $dbname);

$response = array();
if ($_POST['id']) {

    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT lcv_num,
    lcv_registered_to,
    temperature_gauge_id,
    temperature_gauge_make,
    temperature_model,
    temperature_last_calibration_date,
    temperature_claibration_cycle,
    pressure_gauge_id,
    pressure_gauge_make,
    pressure_gauge_model,
    pressure_gauge_claibration_date,
    pressure_gauge_calibration_cycle,
    stationary_cascade_id,
    stationary_cascade_make,
    stationary_cascade_model,
    stationary_cascade_serial_number,
    stationary_hydrotest_status,
    stationary_cascade_capacity,
    stationary_cascade_hydrotest_status_date,
    stationary_cascade_installation_date
    FROM reg_instrument_lcv 
    WHERE lcv_num = ?");
    $stmt->bind_param("s", $id);
    $result = $stmt->execute();

    if ($result == TRUE) {
        $response['error'] = false;
        $response['message'] = "Retrieval Successful!";
        $stmt->store_result();
        $stmt->bind_result(
            $lcv_num,
            $lcv_registered_to,
            $temperature_gauge_id,
            $temperature_gauge_make,
            $temperature_model,
            $temperature_last_calibration_date,
            $temperature_claibration_cycle,
            $pressure_gauge_id,
            $pressure_gauge_make,
            $pressure_gauge_model,
            $pressure_gauge_claibration_date,
            $pressure_gauge_calibration_cycle,
            $stationary_cascade_id,
            $stationary_cascade_make,
            $stationary_cascade_model,
            $stationary_cascade_serial_number,
            $stationary_hydrotest_status,
            $stationary_cascade_capacity,
            $stationary_cascade_hydrotest_status_date,
            $stationary_cascade_installation_date
        );
        $stmt->fetch();
        $response['lcv_num'] = $lcv_num;
        $response['lcv_registered_to'] = $lcv_registered_to;
        $response['temperature_gauge_id'] = $temperature_gauge_id;
        $response['temperature_gauge_make'] = $temperature_gauge_make;
        $response['temperature_model'] = $temperature_model;
        $response['temperature_last_calibration_date'] = $temperature_last_calibration_date;
        $response['temperature_claibration_cycle'] = $temperature_claibration_cycle;

        $response['pressure_gauge_id'] = $pressure_gauge_id;
        $response['pressure_gauge_make'] = $pressure_gauge_make;
        $response['pressure_gauge_model'] = $pressure_gauge_model;
        $response['pressure_gauge_claibration_date'] = $pressure_gauge_claibration_date;
        $response['pressure_gauge_calibration_cycle'] = $pressure_gauge_calibration_cycle;

        $response['stationary_cascade_id'] = $stationary_cascade_id;
        $response['stationary_cascade_make'] = $stationary_cascade_make;
        $response['stationary_cascade_model'] = $stationary_cascade_model;
        $response['stationary_cascade_serial_number'] = $stationary_cascade_serial_number;
        $response['stationary_hydrotest_status'] = $stationary_hydrotest_status;
        $response['stationary_cascade_capacity'] = $stationary_cascade_capacity;
        $response['stationary_cascade_hydrotest_status_date'] = $stationary_cascade_hydrotest_status_date;
        $response['stationary_cascade_installation_date'] = $stationary_cascade_installation_date;
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect id";
    }
} else {

    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}

echo json_encode($response);
