<?php
// not in considerartion



include '../CNG_API/conn.php';

$response= Array();

function lcv_stage_details($conn, $mgs) {
    $select_lcv = "SELECT distinct Notification_LCV from notification where Notification_MGS = '$mgs'";
    $lcv_result = mysqli_query($conn, $select_lcv);
    
    $lcv_stage_details = array();

    while($lcv_row = $lcv_result->fetch_assoc()) {
        $lcv = $lcv_row['Notification_LCV'];
        $select_lcv_details = "SELECT * from notification where Notification_MGS = '$mgs' and Notification_LCV = '$lcv' order by Notification_Id desc limit 1";
        $lcv_details_result = mysqli_query($conn, $select_lcv_details);
        $lcv_details_row = $lcv_details_result->fetch_assoc();
        
        $lcv_stage_details[$lcv_details_row['Notification_LCV']] = $lcv_details_row['flag'];
    }
    return $lcv_stage_details;
}

function min_time_mgs($mgs_details_object) {
    
}

function schedule_lcv($conn, $lcv, $stage, $mgs, $dbs) {
    echo 'scheduled';
}

function scheduling($conn, $mgs, $dbs) {
    // include '../CNG_API/conn.php';
    echo "\n --- scheduling ---\n";

    echo $mgs, $dbs;
    $select_mgs_details = "SELECT * from luag_dbs_request where MGS = '$mgs' and STATUS = 'Pending'";
    $mgs_details_result = mysqli_query($conn, $select_mgs_details);
    $mgs_count = mysqli_num_rows($mgs_details_result);

    print_r($mgs_details_result);
    echo " ---- '$mgs_count' ----\n";

    echo $mgs_count > 0;

    if($mgs_count > 0) {
        echo " ---- '$mgs_count' ----\n";
        $dbs_request_count = $mgs_count;

        $lcv_stage_details = lcv_stage_details($conn, $mgs);

        echo "\n --- lcv_stage_details ---\n";
        print_r($lcv_stage_details);

        $available_lcv = array();
        foreach($lcv_stage_details as $lcv => $stage) {
            if($stage == 1 || $stage == 2) {
                $available_lcv[$lcv] = $stage;
            }
        }

        echo "\n --- available lcv ---\n";
        print_r($available_lcv);

        if($dbs_request_count == 1 && count($available_lcv) > 0) {
            //allocate the first lcv from $available_lcv array to dbs
            $selected_lcv = array_key_first($available_lcv);
            $selected_lcv_stage = $available_lcv[$lcv];

            echo $selected_lcv, $selected_lcv_stage;

            schedule_lcv($conn, $selected_lcv, $selected_lcv_stage + 1,  $mgs, $dbs);
        }


    } else {
        return 'No Scheduling left!';
    }


}





if(isset($_POST['dbs'])) {

    $dbs = $_POST['dbs'];
    $time = $_POST['time_slot'];
    $reading_id = $_POST['reading_id'];
    
    $check_request_query = "SELECT * from luag_dbs_request where DBS = '$dbs' and STATUS = 'Pending'";
    $check_result = mysqli_query($conn, $check_request_query);
    $request_count = mysqli_num_rows($check_result);

    if($request_count == 0) {

        $mgs_select_sql = "SELECT * from luag_dbs_to_mgs_routes where DBS = '$dbs'";
        $mgs_result = mysqli_query($conn, $mgs_select_sql);

        $mgs_num_rows = mysqli_num_rows($mgs_result);

        if ($mgs_num_rows > 0) {

            $mgs_distance_details = array();

            while ($mgs_row = $mgs_result->fetch_assoc()) {
                print_r($mgs_row);

                if (array_key_exists($mgs_row['MGS'],$mgs_distance_details)) {
                    $distance = $mgs_distance_details[$mgs_row['MGS']][0];
                    $time = $mgs_distance_details[$mgs_row['MGS']][1];

                    // here we are putting the minimum travelling/trip time/duration
                    if($mgs_row['Duration'] < $time) {

                        $mgs_distance_details[$mgs_row['MGS']] = array($mgs_row['Distance'], $mgs_row['Duration']);

                    } else if($mgs_row['Duration'] ==  $time) {

                        if ($mgs_row['Distance'] < $distance) {
                            // array_push($mgs_distance_details, $mgs_row['MGS'], $mgs_row['Distance'], $mgs_row['Duration']);
                            $mgs_distance_details[$mgs_row['MGS']] = array($mgs_row['Distance'], $mgs_row['Duration']);
                        }
                    }
                } else {
                    $mgs_distance_details[$mgs_row['MGS']] = array($mgs_row['Distance'], $mgs_row['Duration']);
                }
                print_r($mgs_distance_details);
                
            }
            echo 'mgs result';
            echo json_encode($mgs_distance_details);

            if(count($mgs_distance_details) > 0) {
                $min_time = Array(
                    'mgs' => '',
                    'distance' => 0,
                    'time' => 1000000
                );

                foreach($mgs_distance_details as $mgs => $details) {
                    echo "----- mgs ------";
                    echo $mgs . "   -   -  ";
                    print_r($mgs);
                    echo " - details -";
                    print_r($details);

                    if($details[1] < $min_time['time']) {
                        $min_time['mgs'] = $mgs;
                        $min_time['distance'] = $details[0];
                        $min_time['time'] =  $details[1];
                    }
                }

                echo "Min time:  \n";
                print_r($min_time);

                $selected_mgs = $min_time['mgs'];


                // insert data in request table

                $req_insert_sql = "INSERT into luag_dbs_request(Reading_id, MGS, DBS, STATUS, Operator_id )
                    values('$reading_id', '$selected_mgs', '$dbs', 'Pending', 'Ritik')";
                $req_insert_result = mysqli_query($conn, $req_insert_sql);

                if($req_insert_result) {
                    
                    scheduling($conn, $selected_mgs, $dbs);

                } else {
                    echo 'unable to generate request';
                }


                // 
            











            } else {
                $response['error'] = true;
                $response['message'] = 'Unable to find route for selected DBS.';
            }


        } else {
            $response['error'] = true;
            $response['message'] = 'No route with any MGS specified for selected DBS.';
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Request already generated.';
    }



} else {
    $response['error'] = true;
    $response['message'] = 'Select DBS first.';
}


echo json_encode($response);



?>