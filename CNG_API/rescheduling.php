<?php
include 'conn.php';
header('Content-Type: application/json; charset=utf-8');

// this API is used by:
// 1. manual_scheduling.js
//


// flow of backtracking and updating the data
// 1. will be considered later: check if lcv already scheduled to updated dbs

// 2. update allocation table
//  2.1 update the status of allocated_dbs to 'Manually Resheduled to updated_dbs'
//  2.2 get Notification_Id of lcv for new dbs
//  2.3 insert row in allocation table

// 3. update request table
//  3.1 update status to previous pending for 'allocated_dbs'

// 4. update notification table
//  4.1 set Notification_DBS to updated_dbs

$response = array();

if (isTheseParametersAvailable(array(
    'allocated_dbs', 'mgs', 'lcv', 'updated_dbs'
))) {
    $lcv = $_POST['lcv'];
    $mgs = $_POST['mgs'];
    $allocated_dbs = $_POST['allocated_dbs']; 
    $updated_dbs = $_POST['updated_dbs'];
    // $operator_id = $_SESSION['user_id'];
    $operator_id = 'Ritik_testing';

    echo "\n - - LCV - -", $lcv, " - -\n";
    echo "\n - - MGS - -", $mgs, " - -\n";
    echo "\n - - Allocated DBS - -", $allocated_dbs, " - -\n";
    echo "\n - - Updated DBS - -", $updated_dbs, " - -\n";
    echo "\n - - Operator - -", $operator_id, " - -\n";

    // $check_res = check_updated_dbs_scheduling($conn, $updated_dbs);
    // echo "\n - - - -", json_encode($check_res), " - -\n";
    $check_res['error'] = false;

    if($check_res['error'] == true) {
        $response['error'] = true;
        $response['message'] = $check_res['message'];

    } else {
        $update_res = update_allocation($conn, $mgs, $lcv, $allocated_dbs, $updated_dbs, $operator_id);
        $response['update_res'] = $update_res;

        echo "\n - update allocation func response - ", json_encode($update_res), "\n";

        if($update_res['error'] == true) {
            
            $response['error'] = true;
            $response['message'] = $update_res['message'];

        } else {
            $prev_request_id = $update_res['prev_request_id'];
            $new_req_id = $update_res['new_request_id'];
            $notification_id = $update_res['notification_id'];

            $flag = true;

            if($prev_request_id != null ) {
                $request_res = update_request($conn, $prev_request_id, $operator_id);
                $response['request_res'] = $request_res;

                echo "\n - update request func response - ", json_encode($request_res), "\n";

                if($request_res['error'] == true) {
                    $response['error'] = true;
                    $response['message'] = 'Unable to update request data in dbs request table';
                    $flag = false;
                } 

            }

            if ($flag == true) {

                $notif_res = update_notification($conn, $notification_id, $lcv, $updated_dbs);
                $response['notif_res'] = $notif_res;
                echo "\n - update notification func response - ", json_encode($notif_res), "\n";

                if($notif_res['error'] == true) {
                    $response['error'] = true;
                    $response['message'] = "Allocation and Request table updated but unable to update notitifation";
                } else {
                    $response['error'] = false;
                    $response['message'] = "'$lcv' Re-scheduled from '$allocated_dbs' to '$updated_dbs' !";
                }
            }
        }
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Enter all Mandatory fields';
}

echo "\n - - response:";
echo json_encode($response);

echo "\n - - manual rescheduling end - - \n";
echo 1;


function check_updated_dbs_scheduling($conn, $dbs) {
    $check_query = "SELECT * from luag_lcv_allocation_to_dbs_request where DBS = '$dbs' and Status = 'Scheduled'";
    $check_result = mysqli_query($conn, $check_query);
    $check_count = mysqli_num_rows($check_result);
    $response = array();
    $check_row = $check_result->fetch_assoc();
    if($check_count > 0) {
        $response['error'] = true;
        $response['message'] = $check_row['LCV_Num'] . " already scheduled to ". $dbs;
    } else {
        $response['error'] = false;
    }
    return $response;

}


function update_allocation($conn, $mgs, $lcv, $allocated_dbs, $updated_dbs, $operator_id) {
    // $update_query = "UPDATE luag_lcv_allocation_to_dbs_request set DBS = '$updated_dbs' where Sno in (
    //     SELECT Sno from luag_lcv_allocation_to_dbs_request 
    //         where LCV_num = '$lcv' and Status = 'Scheduled' 
    //         order by Allocation_date desc
    // )";
    echo "\n - - Update allocation func start - - \n";

    $select_query = "SELECT * from luag_lcv_allocation_to_dbs_request 
        where LCV_num = '$lcv' and DBS='$allocated_dbs' and Status = 'Scheduled'
        Order by Allocation_date desc limit 1";
    $select_result = mysqli_query($conn, $select_query);
    $row_count = mysqli_num_rows($select_result);
    $response = array();

    echo "\n - - select allocation count: $row_count - - \n";

    $alloc_id = null;
    $request_id = null;
    $notification_id = null;

    if($row_count > 0) {
        $row = $select_result->fetch_assoc();
        echo "\n - - select row - -", json_encode($row), " - -\n";

        $alloc_id = $row['Sno'];
        $request_id = $row['Request_id'];
        $notif_id = $row['Notification_Id'];

    } else {
        $response['check_allocation_data_message'] = "No Data found to update scheduling as lcv is not already scheduled.";
    }

        $notif_response = get_notification_data($conn, $notif_id, $lcv);
        $response['get_notification_id_response'] = $notif_response;

        echo "\n - notification response - ", json_encode($notif_response), "\n";

        if($notif_response['error'] == true) {
            
            $response['error'] = true;
            $response['message'] = $notif_response['message'];

        } else {

            $notification_id = $notif_response['notif_id'];
            $new_req_id = "$updated_dbs". date('mdYHis') . '-manual'; 
            
            $insert_query = "INSERT into luag_lcv_allocation_to_dbs_request (
                Request_Id, Notification_Id, MGS, DBS, LCV_num, Status, Operator_id
            ) values (
                '$new_req_id', 
                '$notification_id', 
                '$mgs', 
                '$updated_dbs', 
                '$lcv', 
                'Scheduled', 
                '$operator_id'
            )";

            $insert_result = mysqli_query($conn, $insert_query);

            echo "\n - - insert allocation result - - \n";

            if($insert_result) {

                $insert_id = $conn->insert_id;

                echo "\n - - insert id :", $insert_id, "\n";

                if($alloc_id != null) {
                    $update_query = "UPDATE luag_lcv_allocation_to_dbs_request 
                    set Status = 'Manually Rescheduled to $updated_dbs', 
                    Modifier_operator_id = '$operator_id'
                    where Sno=$alloc_id";

                    $update_result = mysqli_query($conn, $update_query);

                    echo "\n - - update allocation result: ", json_encode($update_result), "\n";
                
                    if($update_result) {

                        $response['error'] = false;
                        $response['prev_request_id'] = $request_id;
                        $response['notification_id'] = $notification_id;
                        $response['new_request_id'] = $new_req_id;
                        $response['insert_id'] = $insert_id;

                    } else {
                        // delete from allocation
                        $del_query = "DELETE from luag_lcv_allocation_to_dbs_request where Sno = $insert_id";
                        $del_result = mysqli_query($conn, $del_query);

                        $response['error'] = true;
                        $response['message'] = 'Unable to Re schedule the LCV at this moment.';

                    }
                } else {
                    $response['allocation_update_message'] = "No Updation in allocation required.";
                }

            } else {
                $response['error'] = true;
                $response['message'] = 'Unable to reschedule the LCV at this moment.';
            }
        }

    echo "\n - - Update allocation func end - - \n";

    return $response;

}

function get_notification_data($conn, $notification_id, $lcv){

    echo "\n - - get notif data func Start - - \n";
    if($notification_id == null) {
        $notification_id = 0;
    }
    $select_query = "SELECT * from notification 
        where Notification_Id >= $notification_id and Notification_LCV = '$lcv' 
        order by create_date desc limit 1";
    $select_result = mysqli_query($conn, $select_query);
    $sel_count = mysqli_num_rows($select_result);
    $response = array();

    if($sel_count > 0) {
        $notif_row = $select_result->fetch_assoc();
        echo "\n - notif row- ", json_encode($notif_row), "\n";

        if($notif_row['flag'] >= 4) {
            $response['error'] = true;
            $response['message'] = 'LCV has already reached at DBS.';
        } else {
            $response['error'] = false;
            $response['notif_id'] = $notif_row['Notification_Id'];
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'No data found in notificcation for selected LCV.';
    }

    echo "\n - - get notif data func end - - \n";

    return $response;
}

function update_request($conn, $req_id, $operator_id) {

    echo "\n - - update request func start - - \n";

    $req_update_query = "UPDATE luag_dbs_request 
        set MGS=null, 
        Route_id=null, 
        Notification_Id=null, 
        STATUS='Previous Pending',  
        Updated_by='$operator_id' 
        where Request_id= '$req_id'";
    $req_update_result = mysqli_query($conn, $req_update_query);

    echo "\n - -req update result: ", json_encode($req_update_result), "\n";

    $response = array();
    if($req_update_result) {
        $response['error'] = false;
        $response['message']="Request Updated Successfully";
    } else {
        $response['error'] = true;
        $response['message'] = "Unable to update LCV scheduing at this moment.";
    }
    
    echo "\n - - update request func end - - \n";

    return $response;
}

function update_notification($conn, $notification_id,  $lcv, $updated_dbs) {

    echo "\n - - update notification func start - - \n";

    $update_notif_query = "UPDATE notification set Notification_DBS = '$updated_dbs' 
        where Notification_Id = $notification_id";
    $update_notif_result = mysqli_query($conn, $update_notif_query);

    // echo json_encode($update_notif_result);
    
    $response = array();

    if($update_notif_result == true) {
        $response['error'] = false;
        $response['message'] = "Notification Updated successfully";
    } else {
        $response['error'] = true;
        $response['message'] = 'Unable to update Notification';
    }

    echo "\n - - update notification func end - - \n";
    return $response;
}


function isTheseParametersAvailable($params) {
    foreach ($params as $param) {
        if (!isset($_POST[$param])) {
            return false;
        }
        // echo $param . " ";
    }
    return true;
}


?>

