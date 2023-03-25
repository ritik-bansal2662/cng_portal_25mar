<?php 

// this api has been used by :
// 1. lcv_status.js
// 2. 

include 'conn.php';
header('Content-Type: application/json; charset=utf-8');

if(isset($_GET['apicall'])){
    $response = array(array());
    switch($_GET['apicall']) {
        case 'lcv':
            $lcv_num=$_GET['lcv_num'];
            $select_sql = "SELECT * from notification where Notification_LCV = '$lcv_num' order by Notification_Date";
            $result = mysqli_query($conn, $select_sql);
            
            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                $trip_array= array(array());
                $trip = array();

                while($row = $result-> fetch_assoc()) {
                    if($row['flag'] == 1 && count($trip) != 0){ // if new trip arrives
                        
                        array_push($trip_array, $trip);
                        $trip = array();
                    }
                    array_push($trip, $row);
                }
                
                array_push($trip_array, $trip);
                $response[$lcv_num] = $trip_array;

            } else {
                $response['data_available'] = false;
                $response['message'] = "No Trips for " . $lcv_num;
            }
            break;
        case 'all':
            if(isset($_GET['date']) && isset($_GET['lcv_num'])) {
                $date = $_GET['date'];
                $lcv_num = $_GET['lcv_num'];
                $id_list= array();
                $id_sql = "SELECT Notification_Id from notification where Notification_LCV = '$lcv_num' and date(Notification_Date) = '$date' and flag = '1'";
                $id_result = mysqli_query($conn, $id_sql);
                
                $id_count = mysqli_num_rows($id_result);
                if($id_count > 0) {
                    while($id_row = $id_result-> fetch_assoc()) {
                    
                        array_push($id_list, $id_row['Notification_Id']);
                    }
                    
                    $trip_array=array(array());
                    foreach($id_list as $id)  {
                    
                        $trip_sql_query = "SELECT * from notification where Notification_LCV = '$lcv_num' and Notification_Id >= '$id' order by Notification_Date limit 6";
                        $trip_result = mysqli_query($conn, $trip_sql_query);
                        $trip_detils_count = mysqli_num_rows($trip_result);

                        $trip = array();
                        while($trip_row = $trip_result -> fetch_assoc()) {
                            array_push($trip, $trip_row);
                        }
                        array_push($trip_array, $trip);
                    }
                    $response[$lcv_num] = $trip_array;

                } else {
                    $response['data_available'] = false;
                    $response['message'] = "No Trips for " . $lcv_num . " on " . $date;
                }
            } else {
                $response['data_available'] = false;
                $response['message'] = "Invalid parameters.";
            }
            break;
        case 'date':
            $date = $_GET['date'];
            // $response=array(array(array()));
            $lcv_list= array(array());
            $lcv_sql = "SELECT Notification_Id, Notification_LCV from notification where date(Notification_date) = '$date' and flag = '1'";
            $lcv_result = mysqli_query($conn, $lcv_sql);
            
            // echo "LCV result \n";
            // print_r($lcv_result);
            // echo "lcv result end \n";

            $lcv_count = mysqli_num_rows($lcv_result);
            if($lcv_count > 0) {
                while($lcv_row = $lcv_result-> fetch_assoc()) {
                    $lcv = $lcv_row['Notification_LCV'];
                    $notif_id = $lcv_row['Notification_Id'];
                    // echo json_encode($lcv_row), "\n";
                    // $lcv_list[$lcv_row['Notification_LCV']] = $lcv_row['Notification_Id'];
                    if(array_key_exists($lcv_row['Notification_LCV'], $lcv_list)){
                        // echo "\n - - key exist - -\n";
                        array_push($lcv_list[$lcv_row['Notification_LCV']], $lcv_row['Notification_Id']);
                        // echo json_encode($lcv_list);
                    } else {
                        // echo "\n - - $lcv key does not exist - -\n";
                        // array_push($lcv_list,
                        //     $lcv_row['Notification_LCV'] => array(
                        //         $lcv_row['Notification_Id']
                        // ));
                        $lcv_list[$lcv_row['Notification_LCV']] = array($lcv_row['Notification_Id']);
                        // echo json_encode($lcv_list);
                        // array_push($lcv_list[$lcv_row['Notification_LCV']], $lcv_row['Notification_Id']);
                    }
                }
                // echo "lcv list: \n",json_encode($lcv_list), "\n";

                // echo "lcv list \n";
                // print_r($lcv_list);
                // echo "lcv list end \n";

                foreach($lcv_list as $vehicle_num => $notification_ids)  {
                    //  and Notification_Id not in (select Notoification_Id from notification where Notification_LCV = '$vehicle_num' and flag = '1' and date(Notification_Date) != '$date'";
                    // echo " - - $vehicle_num - - \n";
                    if($vehicle_num == '0') continue;
                    // echo " - - $vehicle_num - - \n";
                    $trip_array= array(array());
                    $i=1;
                    foreach($notification_ids as $notification_id) {
                        // echo "\n - - - $notification_id - - \n";
                        $vehicle_sql_query = "SELECT * from notification where Notification_LCV = '$vehicle_num' and Notification_Id >='$notification_id' limit 6";
                        $vehicle_result = mysqli_query($conn, $vehicle_sql_query);
                        $veh_detils_count = mysqli_num_rows($vehicle_result);
                        
                        $trip = array();
                        while($vehicle_row = $vehicle_result -> fetch_assoc()) {
                            // if($vehicle_row['flag'] == 1 && count($trip) != 0){
                            //     $trip_array[$i] = $trip;
                            //     $trip = array();
                            //     $i++;
                            // }
                            array_push($trip, $vehicle_row);
                            // echo "vehicle row \n";
                            // print_r($vehicle_row);
                            // echo "vehicle row end \n";
                        }
                        // echo "trip array \n";
                        $trip_array[$i] = $trip;
                        $i++;
                        // echo "\n - - trip array - - \n", json_encode($trip_array);
                    }
                    $response[$vehicle_num] = $trip_array;
                    // array_push($response[$vehicle_num], $trip_array);
                }
                // echo json_encode($lcv_list);

            } else {
                $response['data_available'] = false;
                $response['message'] = "No Trips for " . $date . " date.";
            }
            break;
        default:
            $response['data_available'] = false;
            $response['message'] = "Invalid API call.";
            break;
        
    
    }
    
}
else {
    $response['data_available'] = false;
    $response['message'] = "Invalid API called.";
}

// echo "\n - -response - - \n";
echo json_encode($response);

?>