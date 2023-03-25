<?php

include "conn.php";
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

$lcv_num = $_POST["lcv_num"];
$lcv_registered_to = $_POST["lcv_registered_to"];

$temperature_gauge_id = $_POST["temperature_gauge_id"];
$temperature_gauge_make = $_POST["temperature_gauge_make"];
$temperature_model = $_POST["temperature_model"];
$temperature_last_calibration_date = $_POST["temperature_last_calibration_date"];
$temperature_claibration_cycle = $_POST["temperature_claibration_cycle"];

$pressure_gauge_id = $_POST["pressure_gauge_id"];
$pressure_gauge_make = $_POST["pressure_gauge_make"];
$pressure_gauge_model = $_POST["pressure_gauge_model"];
$pressure_gauge_claibration_date = $_POST["pressure_gauge_claibration_date"];
$pressure_gauge_calibration_cycle = $_POST["pressure_gauge_calibration_cycle"];

$stationary_cascade_id = $_POST["stationary_cascade_id"];
$stationary_cascade_make = $_POST["stationary_cascade_make"];
$stationary_cascade_model = $_POST["stationary_cascade_model"];
$stationary_cascade_serial_number = $_POST["stationary_cascade_serial_number"];
$stationary_hydrotest_status = $_POST["stationary_hydrotest_status"];
$stationary_cascade_capacity = $_POST["stationary_cascade_capacity"];

$stationary_cascade_hydrotest_status_date = $_POST["stationary_cascade_hydrotest_status_date"];
$stationary_cascade_installation_date = $_POST["stationary_cascade_installation_date"];

//$stationary_hydrotest_status = $_POST["Create_User_Id"];
//$stationary_cascade_capacity = $_POST["Modified_User_Id"];



$sql = "INSERT INTO reg_instrument_lcv(lcv_num,
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
)
 VALUES ('$lcv_num',
 '$lcv_registered_to',
 '$temperature_gauge_id',
'$temperature_gauge_make',
'$temperature_model',
'$temperature_last_calibration_date',
'$temperature_claibration_cycle',
'$pressure_gauge_id',
'$pressure_gauge_make',
'$pressure_gauge_model',
'$pressure_gauge_claibration_date',
'$pressure_gauge_calibration_cycle',
'$stationary_cascade_id',
'$stationary_cascade_make',
'$stationary_cascade_model',
'$stationary_cascade_serial_number',
'$stationary_hydrotest_status',
'$stationary_cascade_capacity',
'$stationary_cascade_hydrotest_status_date',
'$stationary_cascade_installation_date')";

$result = mysqli_query($conn, $sql);

if ($result) {
    $response['error'] = false;
    $response['message'] = "Data Insertion Successful!";
} else {
    $response['error'] = true;
    $response['message'] = "Insertion Failed";
}
echo json_encode($response);
mysqli_close($conn);
