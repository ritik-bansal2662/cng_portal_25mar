<?php

include "conn.php";
// $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
$response = array();
if (isset($_GET['apicall'])) {
    session_start();
    switch ($_GET['apicall']) {
        case 'insertGenInfo':
            if (isTheseParametersAvailable(array(
                'Station_id', 'Station_type', 'Station_Name', 'notification_approver_id',
                'Station_In_Charge_Name', 'Station_In_Charge_Contact_Number',
                'Number_Filling_Bays', 'Number_Dispenser_Per_Bay', 'Latitude_Longitude'
                ))) {

                $Station_id = $_POST["Station_id"];
                $Station_type = $_POST["Station_type"];
                if(isset($_POST["mgsId"])){
                    $mgsId =$_POST["mgsId"];
                } else {
                    $mgsId = '';
                }
                $add1=$_POST['Address-l-1'];
                $add2=$_POST['Address-l-2'];
                $add3=$_POST['Address-l-3'];
                $city = $_POST['city'];
                $state=$_POST['state'];
                $postal=$_POST['postal-code'];
                $Station_Name = $_POST['Station_Name'];
                $Station_Address =$add1.", ".$add2.", ".$add3.", ".$city.", ".$state.", ".$postal;
                $notification_approver_id = $_POST["notification_approver_id"];
                $Station_In_Charge_Name = $_POST["Station_In_Charge_Name"];
                $Station_In_Charge_Contact_Number = $_POST["Station_In_Charge_Contact_Number"];
                $Number_Filling_Bays = $_POST["Number_Filling_Bays"];
                $Number_Dispenser_Per_Bay = $_POST["Number_Dispenser_Per_Bay"];
                $Latitude_Longitude = $_POST["Latitude_Longitude"];
                $create_user = $_SESSION['user_id'];

                $stmt = $conn->prepare("SELECT Station_id FROM luag_station_master
                WHERE Station_id=? and Station_type= ?");
                $stmt->bind_param("ss", $Station_id, $Station_type);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response['error'] = true;
                    $response['message'] = "Station Id already exists";
                    $stmt->close();
                } else {
                    $sql = "INSERT INTO luag_station_master(Station_id,Station_type,mgsId,notification_approver_id,Station_Name,Station_Address,Station_In_Charge_Name,
                    Station_In_Charge_Contact_Number,Number_Filling_Bays,Number_Dispenser_Per_Bay,Latitude_Longitude, Create_User_Id, Modified_User_Id)
                    VALUES ('$Station_id','$Station_type','$mgsId','$notification_approver_id','$Station_Name','$Station_Address',
                        '$Station_In_Charge_Name','$Station_In_Charge_Contact_Number','$Number_Filling_Bays',
                        '$Number_Dispenser_Per_Bay','$Latitude_Longitude', '$create_user', ''
                    )";
                    $result = mysqli_query($conn, $sql);
                    //echo $result;
                    if ($result) {
                        $response['error'] = false;
                        $response['message'] = "Station Registered successfully!";
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Unable to register Station at this moment.";
                    }
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;

        case 'updateGenInfo':
            if (isTheseParametersAvailable(array(
                'Station_id', 'Station_type', 'Station_Name',
                'Station_Address', 'Station_In_Charge_Name', 'Station_In_Charge_Contact_Number',
                'Number_Filling_Bays', 'Number_Dispenser_Per_Bay', 'Latitude_Longitude'
            ))) {

                $Station_id = $_POST["Station_id"];
                $Station_type = $_POST["Station_type"];
                if(isset($_POST["mgsId"])){
                    $mgsId =$_POST["mgsId"];
                } else {
                    $mgsId = '';
                }
                $Station_Name = $_POST["Station_Name"];
                $Station_Address = $_POST["Station_Address"];
                $Station_In_Charge_Name = $_POST["Station_In_Charge_Name"];
                $Station_In_Charge_Contact_Number = $_POST["Station_In_Charge_Contact_Number"];
                $Number_Filling_Bays = $_POST["Number_Filling_Bays"];
                $Number_Dispenser_Per_Bay = $_POST["Number_Dispenser_Per_Bay"];
                $Latitude_Longitude = $_POST["Latitude_Longitude"];
                $modified_user = $_SESSION['user_id'];


                $sql = "UPDATE luag_station_master
                    set mgsId='$mgsId',
                    Station_Name='$Station_Name',
                    Station_Address='$Station_Address',
                    Station_In_Charge_Name='$Station_In_Charge_Name',
                    Station_In_Charge_Contact_Number='$Station_In_Charge_Contact_Number',
                    Number_Filling_Bays='$Number_Filling_Bays',
                    Number_Dispenser_Per_Bay='$Number_Dispenser_Per_Bay',
                    Latitude_Longitude='$Latitude_Longitude',
                    Modified_User_Id = '$modified_user'
                    WHERE Station_id='$Station_id'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Station Details Updated Successfully!";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Unable to update details at this moment.";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;
        case 'insertEquipInfo':
            if (isTheseParametersAvailable(array(
                'Station_id', 'Station_type', 'stationary_cascade_id', 'stationary_cascade_make',
                'stationary_cascade_model', 'stationary_cascade_serial_number', 'stationary_cascade_installation_date',
                'stationary_cascade_hydrotest_status_date', 'stationary_hydrotest_status', 'stationary_cascade_capacity', 
                'stationary_cascade_volume', 'stationary_cascade_cylinder_count', 'compressor_id',
                'compressor_make', 'compressor_model', 'compressor_serial_number',
                'compressor_type', 'dispenser_id', 'dispenser_make', 'dispenser_model',
                'dispenser_type'
                ))) {
                $stationary_cascade_reorder_point = $_POST["stationary_cascade_reorder_point"];
                $Station_id = $_POST["Station_id"];
                $Station_type = $_POST["Station_type"];
                if(isset($_POST["mgsId"])){
                    $mgsId =$_POST["mgsId"];
                } else {
                    $mgsId = '';
                }
                $stationary_cascade_id = $_POST["stationary_cascade_id"];
                $stationary_cascade_make = $_POST["stationary_cascade_make"];
                $stationary_cascade_model = $_POST["stationary_cascade_model"];
                $stationary_cascade_serial_number = $_POST["stationary_cascade_serial_number"];
                $stationary_cascade_installation_date = $_POST["stationary_cascade_installation_date"];
                $stationary_cascade_hydrotest_status_date = $_POST["stationary_cascade_hydrotest_status_date"];
                $stationary_hydrotest_status = $_POST["stationary_hydrotest_status"];
                $stationary_cascade_capacity = $_POST["stationary_cascade_capacity"];
                $stationary_cascade_volume = $_POST["stationary_cascade_volume"];
                $stationary_cascade_cylinder_count = $_POST["stationary_cascade_cylinder_count"];
                $compressor_id = $_POST["compressor_id"];
                $compressor_make = $_POST["compressor_make"];
                $compressor_model = $_POST["compressor_model"];
                $compressor_serial_number = $_POST["compressor_serial_number"];
                $compressor_type = $_POST["compressor_type"];
                $dispenser_id = $_POST["dispenser_id"];
                $dispenser_make = $_POST["dispenser_make"];
                $dispenser_model = $_POST["dispenser_model"];
                $dispenser_type = $_POST["dispenser_type"];
                $create_user = $_SESSION['user_id'];

                $stmt = $conn->prepare("SELECT Station_id FROM luag_station_equipment_master
                WHERE Station_id=? and Station_type= ?");
                $stmt->bind_param("ss", $Station_id, $Station_type);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0 && $Station_type != 'Daughter Booster Station') {
                    $response['error'] = true;
                    $response['message'] = 'Equipment Information already exists for this Station.';
                    $stmt->close();
                } else {
                    $sql = "INSERT INTO luag_station_equipment_master(Station_id,
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
                    stationary_cascade_volume,
                    stationary_cascade_cylinder_count,
                    compressor_id,
                    compressor_make,
                    compressor_model,
                    compressor_serial_number,
                    compressor_type,
                    dispenser_id,
                    dispenser_make,
                    dispenser_model,
                    dispenser_type,
                    create_user_id
                    )
                     VALUES ('$Station_id',
                        '$Station_type',
                        '$mgsId',
                        '$stationary_cascade_id',
                        '$stationary_cascade_make',
                        '$stationary_cascade_model',
                        '$stationary_cascade_serial_number',
                        '$stationary_cascade_installation_date',
                        '$stationary_cascade_hydrotest_status_date',
                        '$stationary_hydrotest_status',
                        '$stationary_cascade_capacity',
                        '$stationary_cascade_reorder_point',
                        '$stationary_cascade_volume',
                        '$stationary_cascade_cylinder_count',
                        '$compressor_id',
                        '$compressor_make',
                        '$compressor_model',
                        '$compressor_serial_number',
                        '$compressor_type',
                        '$dispenser_id',
                        '$dispenser_make',
                        '$dispenser_model',
                        '$dispenser_type',
                        '$create_user'
                    )";
                    $result = mysqli_query($conn, $sql);
                    // echo $result."<br>"; 
                    if ($result) {
                        $response['error'] = false;
                        $response['message'] = "Equipment Information inserted successfully!";
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Unable to insert Equipment Information at this moment.";
                    }
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;

        case 'updateEquipInfo':
            if (isTheseParametersAvailable(array(
                'Station_id', 'Station_type', 'stationary_cascade_id', 'stationary_cascade_make','stationary_cascade_reorder_point',
                'stationary_cascade_model', 'stationary_cascade_serial_number', 'stationary_cascade_installation_date',
                'stationary_cascade_hydrotest_status_date', 'stationary_hydrotest_status', 'stationary_cascade_capacity', 'compressor_id',
                'compressor_make', 'compressor_model', 'compressor_serial_number',
                'compressor_type', 'dispenser_id', 'dispenser_make', 'dispenser_model',
                'dispenser_type'
                ))) {
                $stationary_cascade_reorder_point = $_POST["stationary_cascade_reorder_point"];
                $Station_id = $_POST["Station_id"];
                $Station_type = $_POST["Station_type"];
                if(isset($_POST["mgsId"])){
                    $mgsId =$_POST["mgsId"];
                } else {
                    $mgsId = '';
                }
                $stationary_cascade_id = $_POST["stationary_cascade_id"];
                $stationary_cascade_make = $_POST["stationary_cascade_make"];
                $stationary_cascade_model = $_POST["stationary_cascade_model"];
                $stationary_cascade_serial_number = $_POST["stationary_cascade_serial_number"];
                $stationary_cascade_installation_date = $_POST["stationary_cascade_installation_date"];
                $stationary_cascade_hydrotest_status_date = $_POST["stationary_cascade_hydrotest_status_date"];
                $stationary_hydrotest_status = $_POST["stationary_hydrotest_status"];
                $stationary_cascade_capacity = $_POST["stationary_cascade_capacity"];
                $compressor_id = $_POST["compressor_id"];
                $compressor_make = $_POST["compressor_make"];
                $compressor_model = $_POST["compressor_model"];
                $compressor_serial_number = $_POST["compressor_serial_number"];
                $compressor_type = $_POST["compressor_type"];
                $dispenser_id = $_POST["dispenser_id"];
                $dispenser_make = $_POST["dispenser_make"];
                $dispenser_model = $_POST["dispenser_model"];
                $dispenser_type = $_POST["dispenser_type"];
                $modified_user = $_SESSION['user_id'];

                $sql = "UPDATE luag_station_equipment_master set
                    stationary_cascade_reorder_point='$stationary_cascade_reorder_point',
                    stationary_cascade_id='$stationary_cascade_id',
                    stationary_cascade_make='$stationary_cascade_make',
                    stationary_cascade_model='$stationary_cascade_model',
                    stationary_cascade_serial_number='$stationary_cascade_serial_number',
                    stationary_cascade_installation_date='$stationary_cascade_installation_date',
                    stationary_cascade_hydrotest_status_date='$stationary_cascade_hydrotest_status_date',
                    stationary_hydrotest_status='$stationary_hydrotest_status',
                    stationary_cascade_capacity='$stationary_cascade_capacity',
                    compressor_id='$compressor_id',
                    compressor_make='$compressor_make',
                    compressor_model='$compressor_model',
                    compressor_serial_number='$compressor_serial_number',
                    compressor_type='$compressor_type',
                    dispenser_id='$dispenser_id',
                    dispenser_make='$dispenser_make',
                    dispenser_model='$dispenser_model',
                    dispenser_type='$dispenser_type',
                    modified_user_id = '$modified_user'
                    WHERE Station_id='$Station_id'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Station Equipment Information Updated Successfully!";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Unable to update station equipment details at this moment!";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;
        case 'insertInstrumentInfo':
            if (isTheseParametersAvailable(array(
                'Station_id',
                'Station_type',
                'temperature_gauge_id',
                'temperature_gauge_make',
                'temperature_model',
                'temperature_last_calibration_date',
                'temperature_claibration_cycle',
                'low_pressure_gauge_id',
                'low_pressure_gauge_make',
                'low_pressure_gauge_model',
                'low_pressure_gauge_claibration_date',
                'low_pressure_gauge_calibration_cycle',
                'medium_pressure_gauge_id',
                'medium_pressure_gauge_make',
                'medium_pressure_gauge_model',
                'medium_pressure_gauge_claibration_date',
                'medium_pressure_gauge_calibration_cycle',
                'high_pressure_gauge_id',
                'high_pressure_gauge_make',
                'high_pressure_gauge_model',
                'high_pressure_gauge_claibration_date',
                'high_pressure_gauge_calibration_cycle',
                'mass_flow_meter_id',
                'mass_flow_make',
                'mass_flow_model',
                'mass_flow_serial_number',
                'mass_flow_calibration_date',
                'mass_flow_calibration_cycle'
            ))) {

                $Station_id = $_POST["Station_id"];
                $Station_type = $_POST["Station_type"];
                // $mgsId = $_POST["mgsId"];
                if(isset($_POST["mgsId"])){
                    $mgsId =$_POST["mgsId"];
                } else {
                    $mgsId = '';
                }
                $stationary_cascade_id = $_POST["stationary_cascade_id"];
                $temperature_gauge_id = $_POST["temperature_gauge_id"];
                $temperature_gauge_make = $_POST["temperature_gauge_make"];
                $temperature_model = $_POST["temperature_model"];
                $temperature_last_calibration_date = $_POST["temperature_last_calibration_date"];
                $temperature_claibration_cycle = $_POST["temperature_claibration_cycle"];

                $low_pressure_gauge_id = $_POST["low_pressure_gauge_id"];
                $low_pressure_gauge_make = $_POST["low_pressure_gauge_make"];
                $low_pressure_gauge_model = $_POST["low_pressure_gauge_model"];
                $low_pressure_gauge_claibration_date = $_POST["low_pressure_gauge_claibration_date"];
                $low_pressure_gauge_calibration_cycle = $_POST["low_pressure_gauge_calibration_cycle"];

                $medium_pressure_gauge_id = $_POST["medium_pressure_gauge_id"];
                $medium_pressure_gauge_make = $_POST["medium_pressure_gauge_make"];
                $medium_pressure_gauge_model = $_POST["medium_pressure_gauge_model"];
                $medium_pressure_gauge_claibration_date = $_POST["medium_pressure_gauge_claibration_date"];
                $medium_pressure_gauge_calibration_cycle = $_POST["medium_pressure_gauge_calibration_cycle"];

                $high_pressure_gauge_id = $_POST["high_pressure_gauge_id"];
                $high_pressure_gauge_make = $_POST["high_pressure_gauge_make"];
                $high_pressure_gauge_model = $_POST["high_pressure_gauge_model"];
                $high_pressure_gauge_claibration_date = $_POST["high_pressure_gauge_claibration_date"];
                $high_pressure_gauge_calibration_cycle = $_POST["high_pressure_gauge_calibration_cycle"];

                $mass_flow_meter_id = $_POST["mass_flow_meter_id"];
                $mass_flow_make = $_POST["mass_flow_make"];
                $mass_flow_model = $_POST["mass_flow_model"];
                $mass_flow_serial_number = $_POST["mass_flow_serial_number"];
                $mass_flow_calibration_date = $_POST["mass_flow_calibration_date"];
                $mass_flow_calibration_cycle = $_POST["mass_flow_calibration_cycle"];
                $create_user = $_SESSION['user_id'];


                $stmt = $conn->prepare("SELECT Station_id FROM luag_station_instrument_master
                WHERE Station_id=? and Station_type= ?");
                $stmt->bind_param("ss", $Station_id, $Station_type);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0 && $Station_type != 'Daughter Booster Station') {
                    $response['error'] = true;
                    $response['message'] = 'Instrument Information already exists for this Station.';
                    $stmt->close();
                } else {
                    $sql = "INSERT INTO luag_station_instrument_master(Station_id,
                        Station_type,
                        mgsId,
                        stationary_cascade_id,
                        temperature_gauge_id,
                        temperature_gauge_make,
                        temperature_model,
                        temperature_last_calibration_date,
                        temperature_claibration_cycle,
                        low_pressure_gauge_id,
                        low_pressure_gauge_make,
                        low_pressure_gauge_model,
                        low_pressure_gauge_claibration_date,
                        low_pressure_gauge_calibration_cycle,
                        medium_pressure_gauge_id,
                        medium_pressure_gauge_make,
                        medium_pressure_gauge_model,
                        medium_pressure_gauge_claibration_date,
                        medium_pressure_gauge_calibration_cycle,
                        high_pressure_gauge_id,
                        high_pressure_gauge_make,
                        high_pressure_gauge_model,
                        high_pressure_gauge_claibration_date,
                        high_pressure_gauge_calibration_cycle,
                        mass_flow_meter_id,
                        mass_flow_make,
                        mass_flow_model,
                        mass_flow_serial_number,
                        mass_flow_calibration_date,
                        mass_flow_calibration_cycle,
                        create_user_id,
                        modified_user_id
                    )
                    VALUES ('$Station_id',
                        '$Station_type',
                        '$mgsId',
                        '$stationary_cascade_id',
                        '$temperature_gauge_id',
                        '$temperature_gauge_make',
                        '$temperature_model',
                        '$temperature_last_calibration_date',
                        '$temperature_claibration_cycle',
                        '$low_pressure_gauge_id',
                        '$low_pressure_gauge_make',
                        '$low_pressure_gauge_model',
                        '$low_pressure_gauge_claibration_date',
                        '$low_pressure_gauge_calibration_cycle',
                        '$medium_pressure_gauge_id',
                        '$medium_pressure_gauge_make',
                        '$medium_pressure_gauge_model',
                        '$medium_pressure_gauge_claibration_date',
                        '$medium_pressure_gauge_calibration_cycle',
                        '$high_pressure_gauge_id',
                        '$high_pressure_gauge_make',
                        '$high_pressure_gauge_model',
                        '$high_pressure_gauge_claibration_date',
                        '$high_pressure_gauge_calibration_cycle',
                        '$mass_flow_meter_id',
                        '$mass_flow_make',
                        '$mass_flow_model',
                        '$mass_flow_serial_number',
                        '$mass_flow_calibration_date',
                        '$mass_flow_calibration_cycle',
                        '$create_user',
                        ''
                    )";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        $response['error'] = false;
                        $response['message'] = "Instrument Information inserted Successfully!";
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Unable to insert instrument information at this moment.";
                    }
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;
        case 'updateInstrumentInfo':
            if (isTheseParametersAvailable(array(
                'Station_id',
                'Station_type',
                'temperature_gauge_id',
                'temperature_gauge_make',
                'temperature_model',
                'temperature_last_calibration_date',
                'temperature_claibration_cycle',
                'pressure_gauge_id',
                'pressure_gauge_make',
                'pressure_gauge_model',
                'pressure_gauge_claibration_date',
                'pressure_gauge_calibration_cycle',
                'mass_flow_meter_id',
                'mass_flow_make',
                'mass_flow_model',
                'mass_flow_serial_number',
                'mass_flow_calibration_date',
                'mass_flow_calibration_cycle'
            ))) {

                $Station_id = $_POST["Station_id"];
                $Station_type = $_POST["Station_type"];
                if(isset($_POST["mgsId"])){
                    $mgsId =$_POST["mgsId"];
                } else {
                    $mgsId = '';
                }
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
                $mass_flow_meter_id = $_POST["mass_flow_meter_id"];
                $mass_flow_make = $_POST["mass_flow_make"];
                $mass_flow_model = $_POST["mass_flow_model"];
                $mass_flow_serial_number = $_POST["mass_flow_serial_number"];
                $mass_flow_calibration_date = $_POST["mass_flow_calibration_date"];
                $mass_flow_calibration_cycle = $_POST["mass_flow_calibration_cycle"];
                $modified_user = $_SESSION['user_id'];


                $sql = "update luag_station_instrument_master set
                    temperature_gauge_id=  '$temperature_gauge_id',
                    temperature_gauge_make='$temperature_gauge_make',
                    temperature_model='$temperature_model',
                    temperature_last_calibration_date= '$temperature_last_calibration_date',
                    temperature_claibration_cycle='$temperature_claibration_cycle',
                    pressure_gauge_id='$pressure_gauge_id',
                    pressure_gauge_make='$pressure_gauge_make',
                    pressure_gauge_model='$pressure_gauge_model',
                    pressure_gauge_claibration_date='$pressure_gauge_claibration_date',
                    pressure_gauge_calibration_cycle='$pressure_gauge_calibration_cycle',
                    mass_flow_meter_id='$mass_flow_meter_id',
                    mass_flow_make='$mass_flow_make',
                    mass_flow_model='$mass_flow_model',
                    mass_flow_serial_number='$mass_flow_serial_number',
                    mass_flow_calibration_date='$mass_flow_calibration_date',
                    mass_flow_calibration_cycle= '$mass_flow_calibration_cycle',
                    modified_user_id = '$modified_user'
                     where Station_id='$Station_id'and Station_type='$Station_type'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Station Instrument Details Updated Successfully!";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Unable to update station instrument information at this moment.";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;
        case 'readGenInfo':
            if ($_POST['id']) {

                $id = $_POST['id'];
                $stmt = $conn->prepare("SELECT mgsId,Station_Name,Station_Address,Station_In_Charge_Name,
                    Station_In_Charge_Contact_Number,Number_Filling_Bays,Number_Dispenser_Per_Bay,Latitude_Longitude 
                    FROM luag_station_master 
                    WHERE Station_Id = ?");
                $stmt->bind_param("s", $id);
                $result = $stmt->execute();

                if ($result == TRUE) {
                    $response['error'] = false;
                    $response['message'] = "Retrieval Successful!";
                    $stmt->store_result();
                    $stmt->bind_result(
                        $mgsId,
                        $Station_Name,
                        $Station_Address,
                        $Station_In_Charge_Name,
                        $Station_In_Charge_Contact_Number,
                        $Number_Filling_Bays,
                        $Number_Dispenser_Per_Bay,
                        $Latitude_Longitude

                    );
                    $stmt->fetch();
                    $response['mgsId'] = $mgsId;
                    $response['Station_Name'] = $Station_Name;
                    $response['Station_Address'] = $Station_Address;
                    $response['Station_In_Charge_Name'] = $Station_In_Charge_Name;
                    $response['Station_In_Charge_Contact_Number'] = $Station_In_Charge_Contact_Number;
                    $response['Number_Filling_Bays'] = $Number_Filling_Bays;
                    $response['Number_Dispenser_Per_Bay'] = $Number_Dispenser_Per_Bay;
                    $response['Latitude_Longitude'] = $Latitude_Longitude;
                } else {

                    $response['error'] = true;
                    $response['message'] = "Incorrect id";
                }
            } else {

                $response['error'] = true;
                $response['message'] = "Insufficient Parameters";
            }
            break;


        default:
            $response['error'] = true;
            $response['message'] = 'Invalid Operation Called';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}

echo json_encode($response);

function isTheseParametersAvailable($params)
{
    foreach ($params as $param) {
        if (!isset($_POST[$param])) {
            return false;
        }
        // echo $param . " ";
    }
    return true;
}
