<?php
include 'conn.php';
$resposne = array();

if(isTheseParametersAvailable( array(
    'reading_id', 'DBS', 'Stationary_cascade_id', 'Compressor_id', 'compressor_reading',
    'dispenser_id', 'dispenser_reading', 
    'temperature_gauge_id', 'tempreature_gauge_reading',
    'low_pressure_gauge_id', 'low_pressure_gauge_reading', 
    'medium_pressure_gauge_id', 'medium_pressure_gauge_reading', 
    'high_pressure_gauge_id', 'high_pressure_gauge_reading', 
    'mass_flow_meter_id', 'mass_flow_meter_reading', 'time_slot'
))) {
    $dbs = $_POST['DBS'];
    $reading_id = $_POST['reading_id'];
    $stationary_cascade_id = $_POST['Stationary_cascade_id'];
    $compressor_id = $_POST['Compressor_id']; 
    $compressor_reading = $_POST['compressor_reading'];
    $dispenser_id = $_POST['dispenser_id']; 
    $dispenser_reading = $_POST['dispenser_reading']; 
    $temperature_gauge_id = $_POST['temperature_gauge_id']; 
    $tempreature_gauge_reading = $_POST['tempreature_gauge_reading'];
    $low_pressure_gauge_id = $_POST['low_pressure_gauge_id']; 
    $low_pressure_gauge_reading = $_POST['low_pressure_gauge_reading']; 
    $medium_pressure_gauge_id = $_POST['medium_pressure_gauge_id'];
    $medium_pressure_gauge_reading=$_POST['medium_pressure_gauge_reading']; 
    $high_pressure_gauge_id=$_POST['high_pressure_gauge_id'];
    $high_pressure_gauge_reading=$_POST['high_pressure_gauge_reading'];
    $mass_flow_meter_id = $_POST['mass_flow_meter_id']; 
    $mass_flow_meter_reading = $_POST['mass_flow_meter_reading']; 
    $time_slot = $_POST['time_slot'];
    $operator_id = 'Ritik';

    $mass_of_gas_left = calculate_gas(
        $compressor_reading, $dispenser_reading, $tempreature_gauge_reading, $low_pressure_gauge_reading, 
        $medium_pressure_gauge_reading, $high_pressure_gauge_reading
    );



    $check_reading = "SELECT * from luag_dbs_reading_history where
     reading_id = '$reading_id' or 
     (DBS = '$dbs' and Stationary_cascade_id = '$stationary_cascade_id' and Time_slot = '$time_slot' and date(Now()) = date(Create_date))";
    $check_result = mysqli_query($conn, $check_reading);
    $reading_count = mysqli_num_rows($check_result);

    if($reading_count == 0) {

        $insert_reading_query = "INSERT into luag_dbs_reading_history(DBS, Reading_id, Operator_id,
        Stationary_cascade_id, Compressor_id, Compressor_reading,
        Dispenser_id, Dispenser_reading, 
        Temperature_gauge_id, Temperature_gauge_reading,
        Low_pressure_gauge_id, Low_Pressure_gauge_reading, 
        Medium_pressure_gauge_id, Medium_Pressure_gauge_reading, 
        High_pressure_gauge_id, High_Pressure_gauge_reading, 
        Mass_flow_meter_id, Mass_flow_meter_reading, Time_slot, Mass_of_gas_left) 
        values (
            '$dbs', '$reading_id', '$operator_id','$stationary_cascade_id','$compressor_id','$compressor_reading','$dispenser_id','$dispenser_reading',
            '$temperature_gauge_id','$tempreature_gauge_reading','$low_pressure_gauge_id','$low_pressure_gauge_reading',
            '$medium_pressure_gauge_id', '$medium_pressure_gauge_reading', '$high_pressure_gauge_id','$high_pressure_gauge_reading',
            '$mass_flow_meter_id','$mass_flow_meter_reading', '$time_slot', '$mass_of_gas_left'
        )";

        $insert_result = mysqli_query($conn, $insert_reading_query);

        if($insert_result) {
            $resposne['error'] = false;
            $response['message'] = 'Readings inserted successfully.';

            $reorder_check_query = "SELECT stationary_cascade_reorder_point from luag_station_equipment_master where stationary_cascade_id = '$stationary_cascade_id'";
            $reorder_result = mysqli_query($conn, $reorder_check_query);
            $reorder_check_row = $reorder_result->fetch_assoc();

            // if reorder point is hit the insert into request table

            if($mass_of_gas_left <= $reorder_check_row['stationary_cascade_reorder_point']){

                // generating request
                $request_insert_query = "INSERT INTO luag_dbs_request(Request_id, Reading_id, DBS, STATUS, Operator_id)
                    values (concat('$reading_id', '$dbs', Now()), '$reading_id', '$dbs', 'New Request', '$operator_id')";
                $request_result = mysqli_query($conn, $request_insert_query);

                if($request_result) {
                    $response['req_error'] = false;
                    $response['req_message'] = 'Request generated successfully.';
                } else {
                    $response['req_error'] = false;
                    $response['req_message'] = 'Reorder point hit but unable to generate request.';
                }
            
            } else {
                $response['reorder_msg'] = "Reorder point not hit.";
            }



        } else {
            $resposne['error'] = true;
            $response['message'] = 'Unable to insert readings at this moment.';
        }

    } else {
        $resposne['error'] = true;
        $response['message'] = 'Record already exists.';
    }


} else {
    $resposne['error'] = true;
    $response['message'] = 'All Fields not set.';
}

echo json_encode($response);


function calculate_gas(
    $compressor_reading, $dispenser_reading, $tempreature_gauge_reading, $low_pressure_gauge_reading, 
    $medium_pressure_gauge_reading, $high_pressure_gauge_reading
) {
    // to be implemented with the formula PV = nRT
    return 20;
}

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

?>