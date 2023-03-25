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
    $stmt = $conn->prepare("SELECT Station_id,
    Station_type,
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
    mass_flow_meter_id,
    mass_flow_make,
    mass_flow_model,
    mass_flow_serial_number,
    mass_flow_calibration_date,
    mass_flow_calibration_cycle
    FROM luag_station_instrument_master 
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
            $mass_flow_meter_id,
            $mass_flow_make,
            $mass_flow_model,
            $mass_flow_serial_number,
            $mass_flow_calibration_date,
            $mass_flow_calibration_cycle
        );
        $stmt->fetch();
        $response['Station_id'] = $Station_id;
        $response['Station_type'] = $Station_type;
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
        $response['mass_flow_meter_id'] = $mass_flow_meter_id;
        $response['mass_flow_make'] = $mass_flow_make;
        $response['mass_flow_model'] = $mass_flow_model;
        $response['mass_flow_serial_number'] = $mass_flow_serial_number;
        $response['mass_flow_calibration_date'] = $mass_flow_calibration_date;
        $response['mass_flow_calibration_cycle'] = $mass_flow_calibration_cycle;
    } else {

        $response['error'] = true;
        $response['message'] = "Incorrect id";
    }
} else {

    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}

echo json_encode($response);
