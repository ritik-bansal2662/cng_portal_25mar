<?php

include "conn.php";
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
$response = array();
$response['parm'] = '';
if (isset($_GET['apicall'])) {
    switch ($_GET['apicall']) {
        case 'insertLcvGenInfo':
            if (isTheseParametersAvailable(array(
                'Lcv_Num', 'Lcv_Registered_To', 'Vechicle_Type', 'Chassis_Num', 'Engine_Num',
                'Cascade_Capacity', 'Lcv_Maker', 'Fuel_Type'
            ))) {

                $lcv_num = $_POST["Lcv_Num"];
                $lcv_registered_to = $_POST["Lcv_Registered_To"];
                $vechicle_type = $_POST["Vechicle_Type"];
                $chassis_num = $_POST['Chassis_Num'];
                $engine_num = $_POST['Engine_Num'];
                $cascade_capacity = $_POST['Cascade_Capacity'];
                $lcv_maker = $_POST['Lcv_Maker'];
                $fuel_type = $_POST['Fuel_Type'];

                $stmt = $conn->prepare("SELECT * FROM reg_lcv
                WHERE Lcv_Num=? and Lcv_Registered_To= ?");
                $stmt->bind_param("ss", $lcv_num, $lcv_registered_to);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response['error'] = true;
                    $response['message'] = "LCV already exists";
                    $stmt->close();
                } else {
                    $sql = "insert into reg_lcv (Lcv_Num,Lcv_Registered_To,Vechicle_Type,Chassis_Num,Engine_Num,Cascade_Capacity,Lcv_Maker,Fuel_Type, lcv_status) values 
                    ('$lcv_num','$lcv_registered_to','$vechicle_type','$chassis_num','$engine_num','$cascade_capacity'
                    ,'$lcv_maker','$fuel_type', 'not known')";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        $response['error'] = false;
                        $response['message'] = "LCV Registered Successfully!";
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Unable to register LCV at this moment.";
                    }
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;

        case 'updateLcvGenInfo':
            if (isTheseParametersAvailable(array(
                'Lcv_Num', 'Lcv_Registered_To', 'Vechicle_Type', 'Chassis_Num', 'Engine_Num',
                'Cascade_Capacity', 'Lcv_Maker', 'Fuel_Type'
            ))) {

                $lcv_num = $_POST["Lcv_Num"];
                $lcv_registered_to = $_POST["Lcv_Registered_To"];
                $vechicle_type = $_POST["Vechicle_Type"];
                $chassis_num = $_POST['Chassis_Num'];
                $engine_num = $_POST['Engine_Num'];
                $cascade_capacity = $_POST['Cascade_Capacity'];
                $lcv_maker = $_POST['Lcv_Maker'];
                $fuel_type = $_POST['Fuel_Type'];

                $sql = "update reg_lcv set 
                Vechicle_Type='$vechicle_type',
                Chassis_Num='$chassis_num',
                Engine_Num='$engine_num',
                Cascade_Capacity='$cascade_capacity',
                Lcv_Maker='$lcv_maker',
                Fuel_Type='$fuel_type' Where lcv_num='$lcv_num'";

                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Details Updated successfully!";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Unable to update details at this moment";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;
        case 'insertLcvCascadeInfo':
            if (isTheseParametersAvailable(array(
                'lcv_num', 'lcv_registered_to', 'temperature_gauge_id', 'temperature_gauge_make', 'temperature_model',
                'temperature_last_calibration_date', 'temperature_claibration_cycle', 'pressure_gauge_id',
                'pressure_gauge_make', 'pressure_gauge_model', 'pressure_gauge_claibration_date',
                'pressure_gauge_calibration_cycle', 'stationary_cascade_id',
                'stationary_cascade_make', 'stationary_cascade_model', 'stationary_cascade_serial_number',
                'stationary_hydrotest_status', 'stationary_cascade_capacity',
                'stationary_cascade_hydrotest_status_date', 'stationary_cascade_installation_date'
            ))) {

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

                $stmt = $conn->prepare("SELECT * FROM reg_instrument_lcv
                    WHERE Lcv_Num=? and Lcv_Registered_To= ?");
                $stmt->bind_param("ss", $lcv_num, $lcv_registered_to);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response['error'] = true;
                    $response['message'] = "Cascade information for selected LCV already exists.";
                    $stmt->close();
                } else {
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
                        $response['message'] = "Cascade Information Inserted successfully!";
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Unable to insert Cascade Information at this moment.";
                    }
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Enter all Mandatory fields';
            }
            break;


        case 'updateLcvCascadeInfo':
            if (isTheseParametersAvailable(array(
                'lcv_num', 'lcv_registered_to', 'temperature_gauge_id', 'temperature_gauge_make', 'temperature_model',
                'temperature_last_calibration_date', 'temperature_claibration_cycle', 'pressure_gauge_id',
                'pressure_gauge_make', 'pressure_gauge_model', 'pressure_gauge_claibration_date',
                'pressure_gauge_calibration_cycle', 'stationary_cascade_id',
                'stationary_cascade_make', 'stationary_cascade_model', 'stationary_cascade_serial_number',
                'stationary_hydrotest_status', 'stationary_cascade_capacity',
                'stationary_cascade_hydrotest_status_date', 'stationary_cascade_installation_date'
            ))) {

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

                $sql = "update reg_instrument_lcv set 
                        temperature_gauge_id= '$temperature_gauge_id',
                        temperature_gauge_make= '$temperature_gauge_make',
                        temperature_model='$temperature_model',
                        temperature_last_calibration_date='$temperature_last_calibration_date',
                        temperature_claibration_cycle='$temperature_claibration_cycle',
                        pressure_gauge_id='$pressure_gauge_id',
                        pressure_gauge_make='$pressure_gauge_make',
                        pressure_gauge_model='$pressure_gauge_model',
                        pressure_gauge_claibration_date='$pressure_gauge_claibration_date',
                        pressure_gauge_calibration_cycle='$pressure_gauge_calibration_cycle',
                        stationary_cascade_id='$stationary_cascade_id',
                        stationary_cascade_make='$stationary_cascade_make',
                        stationary_cascade_model='$stationary_cascade_model',
                        stationary_cascade_serial_number='$stationary_cascade_serial_number',
                        stationary_hydrotest_status='$stationary_hydrotest_status',
                        stationary_cascade_capacity='$stationary_cascade_capacity',
                        stationary_cascade_hydrotest_status_date='$stationary_cascade_hydrotest_status_date',
                        stationary_cascade_installation_date='$stationary_cascade_installation_date'
                        Where lcv_num='$lcv_num'";

                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Details Updated successfully!";
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




// <?php

// include "conn.php";
// $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
// //  $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);


// $lcv_num = $_POST["Lcv_Num"];
// $lcv_registered_to = $_POST["Lcv_Registered_To"];
// $vechicle_type = $_POST["Vechicle_Type"];
// $chassis_num = $_POST['Chassis_Num'];
// $engine_num = $_POST['Engine_Num'];
// $cascade_capacity = $_POST['Cascade_Capacity'];
// $lcv_maker = $_POST['Lcv_Maker'];
// $fuel_type = $_POST['Fuel_Type'];

// $stmt = $conn->prepare("SELECT * FROM reg_lcv
// WHERE Lcv_Num=? and Lcv_Registered_To= ?");
// $stmt->bind_param("ss", $Lcv_Num, $Lcv_Registered_To);
// $stmt->execute();
// $stmt->store_result();

// if ($stmt->num_rows > 0) {
//     $response['error'] = true;
//     $response['message'] = 'Station Id already exists';
//     $stmt->close();
// } else {

//     $Sql_Query = "insert into reg_lcv (Lcv_Num,Lcv_Registered_To,Vechicle_Type,Chassis_Num,Engine_Num,Cascade_Capacity,Lcv_Maker,Fuel_Type) values 
//  ('$lcv_num','$lcv_registered_to','$vechicle_type','$chassis_num','$engine_num','$cascade_capacity'
//  ,'$lcv_maker','$fuel_type')";

//     $result = mysqli_query($conn, $Sql_Query);

//     if ($result) {
//         $response['error'] = false;
//         $response['message'] = "Data Insertion Successful!";
//     } else {
//         $response['error'] = true;
//         $response['message'] = "Insertion Failed";
//     }
// }

// echo json_encode($response);
// mysqli_close($conn);
