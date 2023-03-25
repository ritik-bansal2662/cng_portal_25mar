<?php 
// this file have to run after every 10 sec 

// check of gas left in LCV when it reached back to mgs - 13 feb 2023 : point to be discussed

include 'conn.php';
header('Content-Type: application/json; charset=utf-8');


{
    echo " - - Scheduling engine start - - \n";

    $available_lcv = get_available_lcv($conn);

    echo "\n - - Available LCV - - - \n";
    echo json_encode($available_lcv);


    // first we will try to fulfill all the requests which are pending from previous run
    schedule_previous_pending_requests($conn);

    // we will again check available LCVs
    $available_lcv = get_available_lcv($conn);
    // then we will try to fulfill the requests which are generated now

    $new_requests = check_requests($conn, 'New Request');
    $new_req_count = count($new_requests);
    echo '-- new requests -- \n';
    if(count($new_requests) == 0) {
        echo "\n no new requests \n";
    }else {
        echo "\n - - New Requests count : $new_req_count - - - \n";
        echo json_encode($new_requests);
        $updated_req_data = allocate_mgs($conn, $new_requests, 'Pending');
        echo " \n updated req data(with MGS) \n";
        echo json_encode($updated_req_data), "\n";
    }


    $pending_req = get_pending_requests($conn, 'Pending');
    $pending_req_count = count($pending_req);
    echo " \n --- Pending requests: $pending_req_count --- \n";
    echo json_encode($pending_req);

    schedule($conn, $pending_req);
    





    echo "\n",1 , "\n";
}

// get available LCVs at all MGS
// available lcv are which are in stage 1 or 2 and have not been allocated a DBS
function get_available_lcv($conn) {

    echo "\n - - -  get available lcv function - - \n";
    $lcv_select_query = "WITH ranked_lcv AS (
        SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
        FROM notification AS m
      )
      SELECT * FROM ranked_lcv 
        WHERE rn = 1 and 
        (Notification_DBS is NULL or Notification_DBS = '')"; 
    
    $lcv_result = mysqli_query($conn, $lcv_select_query);

    $lcv_at_mgs = array(array());
    
    while($lcv_row = $lcv_result->fetch_assoc()) {
        echo "\n- -- - lcv row  -- -- \n";
        echo json_encode($lcv_row);
        // array_push(,$lcv_row);
        if( array_key_exists($lcv_row['Notification_MGS'],$lcv_at_mgs)) {
          array_push($lcv_at_mgs[$lcv_row['Notification_MGS']],$lcv_row);
          $lcv_at_mgs[$lcv_row['Notification_MGS']]['count'] += 1;
        } else {
          $lcv_at_mgs[$lcv_row['Notification_MGS']][0] = $lcv_row;
          $lcv_at_mgs[$lcv_row['Notification_MGS']]['count'] = 1;
        }
    }


    echo json_encode($lcv_at_mgs);

    echo "\n - - -  get available lcv function end - - \n";

    return $lcv_at_mgs;

}

function schedule_previous_pending_requests($conn) {

    // first we will try to fulfill all the requests which are pending from previous run

    $requests = check_requests($conn, 'Previous Pending');
    echo '\n-- Previous Pending requests -- \n';
    if(count($requests) == 0) {
        echo "\n no Previous Pending request found \n";
    }else {
        echo json_encode($requests);
        $updated_req_data = allocate_mgs($conn, $requests, 'Previous Pending');
        echo " \n updated req data(with MGS) \n";
        echo json_encode($updated_req_data), "\n";    

        $prev_pending_req = get_pending_requests($conn, 'Previous Pending');
        $prev_pending_req_count = count($prev_pending_req);
        
        echo " \n --- Previous pending requests: $prev_pending_req_count --- \n";
        echo json_encode($prev_pending_req);

        schedule($conn, $prev_pending_req);
    }
}

// check if new requests are generated
function check_requests($conn, $status) {
    $new_requests = array();
    echo "\n - - check req function start - - \n";
    echo "\n - - status: $status - - \n";

    $new_request_select_query = "SELECT * from luag_dbs_request where Status = '$status'";
    $new_request_result = mysqli_query($conn, $new_request_select_query);
    $new_request_count = mysqli_num_rows($new_request_result);

    if($new_request_count > 0 ) {
        while($new_req_row = $new_request_result->fetch_assoc()) {
            $new_requests[$new_req_row['Request_id']] = array(
                $new_req_row['Reading_id'], 
                $new_req_row['MGS'], 
                $new_req_row['DBS']
            );
        }
    } else {
        echo "\n zero $status requests found. \n";
    }

    echo "\n - - check req function end - - \n";

    return $new_requests;

}


// allocate mgs to dbs request compairing travelling time and availability of lcv
function allocate_mgs($conn, $requests_data, $status) {
    echo "\n ---- allocate mgs function ----- \n";
    global $available_lcv;

    foreach($requests_data as $req_id => $req_details) {
        $reading_id = $req_details[0];
        $dbs = $req_details[2];

        // getting route details of dbs with other mgs
        $mgs_select_sql = "SELECT * from luag_dbs_to_mgs_routes where DBS = '$dbs'";
        $mgs_result = mysqli_query($conn, $mgs_select_sql);

        $mgs_num_rows = mysqli_num_rows($mgs_result);

        // if DataBase have record of route of this DBS
        if ($mgs_num_rows > 0) {

            $mgs_distance_details = array();

            // iterating the data fetched from database
            while ($mgs_row = $mgs_result->fetch_assoc()) {
                echo json_encode($mgs_row);

                if (array_key_exists($mgs_row['MGS'],$mgs_distance_details)) {
                    $distance = $mgs_distance_details[$mgs_row['MGS']][0];
                    $time = $mgs_distance_details[$mgs_row['MGS']][1];

                    // here we are putting the minimum travelling/trip time/duration
                    if($mgs_row['Duration'] < $time) {

                        $mgs_distance_details[$mgs_row['MGS']] = array($mgs_row['Distance'], $mgs_row['Duration'], $mgs_row['Route_id']);

                        $mgs_distance_object[$mgs_row['MGS']] = array(
                            'Distance' => $mgs_row['Distance'], 
                            'Duration' => $mgs_row['Duration'], 
                            'Route_id' => $mgs_row['Route_id']
                        );

                    } else if($mgs_row['Duration'] ==  $time && $mgs_row['Distance'] < $distance) {


                        $mgs_distance_details[$mgs_row['MGS']] = array($mgs_row['Distance'], $mgs_row['Duration'], $mgs_row['Route_id']);
                        $mgs_distance_object[$mgs_row['MGS']] = array(
                            'Distance' => $mgs_row['Distance'], 
                            'Duration' => $mgs_row['Duration'], 
                            'Route_id' => $mgs_row['Route_id']
                        );
                    }
                } else {
                    //first check if lcv available at that mgs

                    // checking if lcv available at this lcv and count of lcv must not be zero
                    if(array_key_exists($mgs_row['MGS'], $available_lcv) && $available_lcv[$mgs_row['MGS']]['count'] > 0){ 

                        $mgs_distance_details[$mgs_row['MGS']] = array($mgs_row['Distance'], $mgs_row['Duration'], $mgs_row['Route_id']);
                        $mgs_distance_object[$mgs_row['MGS']] = array(
                            'Distance' => $mgs_row['Distance'], 
                            'Duration' => $mgs_row['Duration'], 
                            'Route_id' => $mgs_row['Route_id']
                        );
                    } else {
                        echo "\n - - - no LCV available at". $mgs_row['MGS'] ." - - - -\n";
                    }


                }
                echo json_encode($mgs_distance_details);
            }
            echo '\n - - -mgs result - - -\n';
            echo json_encode($mgs_distance_details);


            // case to be handled
            // if all MGS have no availability of LCV then remove the request(DBS) form the request object
            // count($mgs_distance_details);



            // here we are getting the MGS which have less travelling time from the selected DBS
            // as of now we are considering that the MGS will have availability of LCV
            // later on we will also consider if the MGS have no availability of LCV
            // in this case we will move to second most nearest MGS
            // (the MGS which have second most least travelling time from selected DBS).

            if(count($mgs_distance_details) > 0) {
                $min_time = Array(
                    'mgs' => '',
                    'distance' => 0,
                    'time' => 1000000,
                    'route_id' => ''
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
                        $min_time['route_id'] =  $details[2];
                    }
                }

                echo "Min time:  \n";
                echo json_encode($min_time);

                $selected_mgs = $min_time['mgs'];
                $selected_route_id = $min_time['route_id'];


                // update the request table and insert the mgs for selected DBS
                // and reduce the count of avaiable LCV at that MGS
                $mgs_update_query = "UPDATE luag_dbs_request set 
                    MGS = '$selected_mgs',
                    Route_id = '$selected_route_id',
                    Status = '$status'
                    where Request_id = '$req_id'";
                $mgs_update_result = mysqli_query($conn, $mgs_update_query);

                if($mgs_update_result) {
                    echo "\n - - MGS('$selected_mgs') allocated successfully to '$dbs' - - -\n";
                    $requests_data[$req_id][1] = $selected_mgs;
                    $requests_data[$req_id][3] = $status;

                    // reduce the count of LCV available at MGS as it is allocated to DBS request
                    // $available_lcv[$selected_mgs]['count'] -= 1;  // will consider it later
                } else {
                    echo "\n - - Unable to allocate mgs to the '$dbs'. - - \n";
                }




            } else {
                echo "\n - - no MGS for '$dbs'. - - \n";
            }
        } else {
            echo "\n - - No route found for selected '$dbs'. - - - \n";
        }


    }

    echo "\n", json_encode($requests_data), "\n";




    return $requests_data;

}


function schedule($conn, $pending_req) {
    //1. get pending requests order by mgs
    // 1.1 join can be used to get distance and time in one query and arrange in priority queue
    //
    //2. get lcv(stage 1 and stage 2) for each mgs and arrange in priority queue
    //3. FIFO(tagging)
    //4. 3rd PQ

    // $pending_req = get_pending_requests($conn, 'Pending');
    echo " \n --- pending requests --- \n";
    echo json_encode($pending_req);

    $pending_req_count = count($pending_req);


    $temp_pending_req = array_key_first($pending_req);
    echo "\n\n - - first req - - \n";
    echo "\n - - $temp_pending_req -  - \n";
    
    $pq_pending_req = new SplPriorityQueue();


    if($pending_req_count > 0) {

        foreach($pending_req as $req_id => $req_details) {
            if($pending_req[$temp_pending_req]['MGS'] != $req_details['MGS']){
                // get PQ of LCV
                // allocate lcv and send the notification for lcv stage
                // start new priority queue
                // temp_pending_req = new mgs
                // insert new mgs in PQ
                // $req_details['request_id'] = $req_id;

                $mgs = $pending_req[$temp_pending_req]['MGS'];

                $lcv_pq = get_lcv_stage($conn, $pending_req[$temp_pending_req]['MGS']);
                if($lcv_pq->count() == 0) {
                    echo "\n unable to allocate mgs. because no lcv available \n";
                    
                    // call for request generation as there is no lcv at this MGS
                    $request_count = $pq_pending_req->count();
                    // generate_empty_lcv_request($conn, $pending_req[$temp_pending_req]['MGS'], $request_count);

                } else {
                    allocate_lcv_to_request($conn, $lcv_pq, $pq_pending_req);

                    $pq_pending_req = new SplPriorityQueue();
                    $temp_pending_req = $req_id;
                    $pq_pending_req->insert($req_details, $req_details['duration']);
                }

            } else {
                $pq_pending_req->insert($req_details, $req_details['duration']);
            }
        }

        // the above loop will not allocate for last request
        // we have to write theis code again to allocate lcv to last request
        $lcv_pq = get_lcv_stage($conn, $pending_req[$temp_pending_req]['MGS']);
        if($lcv_pq->count() == 0) {
            echo "\n unable to allocate mgs. because no lcv available \n";
        } else {
            allocate_lcv_to_request($conn, $lcv_pq, $pq_pending_req);
        }
    } else {
        echo "\n - - -No Pending Requests Found - - \n";
    }

    echo "\n - - - Scheduling End - - \n";

}


// Get pending requests along with distance and time of route of DBS and MGS
function get_pending_requests($conn, $status) {


    // reason for this query ?
    // join is used to get details of routes

    $select_query = "SELECT * FROM `luag_dbs_request` a, `luag_dbs_to_mgs_routes` b 
        WHERE a.Route_id = b.Route_id and a.Status = '$status' order by a.MGS";
    
    $select_result = mysqli_query($conn, $select_query);

    $pending_req = array();
    while($row = $select_result->fetch_assoc()) {
        $pending_req[$row['Request_id']] = array(
            'MGS' => $row['MGS'],
            'DBS' => $row['DBS'],
            'distance' => $row['Distance'],
            'duration' => $row['Duration'],
            'time_slot' => $row['Time_slot'],
            'request_id' => $row['Request_id']
        );
    }

    return $pending_req;
}

function get_lcv_stage($conn, $mgs) {

    // this query will select last occurrence/record of all LCVs of the specified mgs
    // $lcv_select_query = "WITH ranked_lcv AS (
    //     SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
    //     FROM notification AS m
    //   )
    //   SELECT * FROM ranked_lcv 
    //     WHERE rn = 1 and 
    //     Notification_MGS = '$mgs' and 
    //     (Notification_DBS is NULL or Notification_DBS = '')"; 
        // select where dbs is null and flag in (1,2) :=> when DBS = '' this means tha LCV is in stage 1 or 2
        // if LCV is in nay pther stage then it means it have been allocated a DBS to reach
    
    // $lcv_select_result = mysqli_query($conn, $lcv_select_query);
    // $lcv_count = mysqli_num_rows($lcv_select_result);

    global $available_lcv;

    $lcv_priority_queue = new SplPriorityQueue();

    if($available_lcv[$mgs]['count'] > 0) {
        // while($lcv_row = $lcv_select_result->fetch_assoc()){
        //     $lcv_priority_queue->insert($lcv_row, $lcv_row['flag']);
        // }
        foreach($available_lcv[$mgs] as $index => $lcv_details) {
            echo "\n - - available lcv details - - -\n";
            echo json_encode($lcv_details), "\n";

            if($index == 'count') {continue;}

            $lcv_priority_queue->insert($lcv_details , $lcv_details['flag']);
            // $lcv_priority_queue->insert(array(
            //     'Notification_LCV' => $lcv_details['Notification_LCV'],
            //     'flag' => $lcv_details['flag'] 
            // ), $lcv_details['flag']);
        }
    } else {
        echo "\n No lcv found for selected mgs - '$mgs'.\n";
    }



    return $lcv_priority_queue;
}


function allocate_lcv_to_request($conn, $lcv_pq, $request_pq) {

    echo "\n\n\n - - - - Allocation function - - - \n\n";
    // $mgs = '';

    while($request_pq->valid() && $lcv_pq->valid()) {

        echo "\n\n - - - - Allocation while loop - - - \n\n";

        $curr_req = $request_pq->current();
        $curr_lcv = $lcv_pq->current();

        echo "\n --- req --- \n";
        echo json_encode($request_pq->current());
        echo " \n -- - - - \n";
        print_r($curr_req);

        echo "\n --- lcv --- \n";
        echo json_encode($lcv_pq->current());
        echo " \n -- - - - \n";
        echo json_encode($curr_lcv), "\n";

        $lcv_id = $curr_lcv['Notification_LCV'];
        $flag = $curr_lcv['flag'] + 1;
        $notif_id = $curr_lcv['Notification_Id'];
        // echo "--- flag ---", $flag;
        $mgs = $curr_req['MGS'];
        $dbs = $curr_req['DBS'];
        $route_duration = $curr_req['duration'];
        $route_distance = $curr_req['distance'];
        $req_id = $curr_req['request_id'];

        //instead of inserting new row in notification table,
        // we will update dbs column of last row of that lcv

        // $insert_lcv_notification = "INSERT into notification (Notification_LCV, Notification_MGS, 
        //     Notification_DBS, operator_id, Notification_Message, status, flag, Notification_approver) values (
        //         '$lcv_id', ' $mgs', '$dbs', 'Scheduling Engine', 'Scheduled by Scheduling Engine', 'Pending', '$flag', ''
        //     )";

        // $lcv_notif_result = mysqli_query($conn, $insert_lcv_notification);
        
        // $select_notification_id_query = "SELECT LAST_INSERT_ID()";
        // $notification_id_result = mysqli_query($conn, $select_notification_id_query);
        // echo "\n ---- nitification id ---- \n";
        // echo json_encode($notification_id_result);

        // $last_notif_row = $notification_id_result->fetch_assoc();

        // echo "\n --- last notif row ----- \n";
        // echo json_encode($last_notif_row);
        // $last_notif_id = $last_notif_row['LAST_INSERT_ID()'];
        // echo "\n --- - - last notif id = '$last_notif_id'\n";

        $update_notif_dbs_query = "UPDATE notification set Notification_DBS = '$dbs' where Notification_Id = '$notif_id'";
        $update_notif_result = mysqli_query($conn, $update_notif_dbs_query);

        // $update_notif_result = update_table($conn, 'notification', 'Notification_DBS', $dbs, 'Notification_Id', $notif_id);


        if($update_notif_result) {
            // update in request table
            // insert in allocation table

            $update_req_query = "UPDATE luag_dbs_request set 
                Notification_id = '$notif_id', Status = 'LCV Allocated' 
                where Request_id = '$req_id'";
            $update_req_result = mysqli_query($conn, $update_req_query);

            if($update_req_result) {
                // insert into allocation table

                echo "\n - - - -request updated - - - -\n";

                $insert_allocation_query = "INSERT into luag_lcv_allocation_to_dbs_request(
                    Request_id, Notification_Id, MGS, DBS, LCV_Num, Route_Duration, Route_Distance, Operator_id
                ) values (
                    '$req_id', '$notif_id', '$mgs', '$dbs', '$lcv_id', '$route_duration', 
                    '$route_distance', 'Scheduling Engine'
                )";

                $allocation_result = mysqli_query($conn, $insert_allocation_query);

                if($allocation_result) {
                    // update status of request in dbs_request table

                    echo "\n - - - allocation successful for '$dbs' request with MGS - '$mgs' and lcv - '$lcv_id'\n";

                    $update_req_status = "UPDATE  luag_dbs_request set 
                        Status = 'Fulfilled' where Request_id = '$req_id'";
                    $update_req_status_result = mysqli_query($conn, $update_req_status);


                } else {
                    // remove notif id from req table of this request
                    // del notification

                    echo "\n - - unable to allocate lcv to dbs - '$dbs' request.- -\n";

                    $remove_notif_id_query = "UPDATE luag_dbs_request set 
                        Notification_id = NULL, Status = 'Pending' where Request_id = '$req_id'";
                    $remove_id_result = mysqli_query($conn, $remove_notif_id_query);

                    // $remove_dbs_query = update_table($conn, 'notification', 'Notification_DBS', '', 'Notification_Id', $notif_id);

                    $remove_dbs_query = "UPDATE notification set Notification_DBS = '' where Notification_Id = '$notif_id'";
                    $remove_dbs_result = mysqli_query($conn, $remove_dbs_query);

                    // del_from_table($conn, 'notification', 'Notification_Id', $notif_id);

                }


            } else { 
                // if update is not done in request table then roll back and 
                // del the record from notification table having notification_id = last_notif_id

                echo "\n - - - -request not updated - - - -\n";
                // del_from_table($conn, 'notification', 'Notification_Id', $notif_id);

                $remove_dbs_query = "UPDATE notification set Notification_DBS = '' where Notification_Id = '$notif_id'";
                $remove_dbs_result = mysqli_query($conn, $remove_dbs_query);

            }

        }

        echo PHP_EOL; // end of line

        $request_pq->next();
        $lcv_pq->next();
        // break;
    }

    // status of requests which are left from LCV allocation will be updated to prev-pending
    // iterate $request_pq to get left requests

    $remaining_req_count = $request_pq->count();
    if($remaining_req_count > 0) {

        echo "\n - - $remaining_req_count requests after scheduling - - \n";

        // generate_empty_lcv_request($conn, $mgs, $remaining_req_count);
        remaining_requests($conn, $request_pq);
    } else {
        echo "\n - - all requests fulfilled for $mgs - - - \n";
    }


}


function remaining_requests($conn, $remaining_req) {
    // this function will det the status of remaining requests to 'Previous Pending'
    // this will accept priority Queue of the requests as parameter and iterate it
    // 

    echo "\n - - remining requests function start - - -\n";

    while($remaining_req->valid()){
        $curr_req = $remaining_req->current();

        $dbs = $curr_req['DBS'];
        $mgs = $curr_req['MGS'];
        $req_id = $curr_req['request_id'];

        $update_req_query = "UPDATE luag_dbs_request set 
        Status = 'Previous Pending' 
        where Request_id = '$req_id'";

        $update_req_result = mysqli_query($conn, $update_req_query);
        if($update_req_result) {
            // echo "\n- - - status set to previous pending of req id $req_id, $mgs - - \n";
            echo "\n- - - backtracked for req id $req_id, dbs $dbs - - \n";
        } else {
            echo "\n- - -unable to backtrack for req id $req_id, $dbs - - \n";
        }

        

        $remaining_req->next();

    }

    echo "\n - - remining requests function end - - -\n";
}


function generate_empty_lcv_request($conn, $mgs, $count) {
    // this function will generate request from MGS for empty LCV to the engine
    // request will be inserted in luag_empty_lcv_requests
    // first it will check if the request is already generated today and not fulfilled

    echo "\n - - generate empty lcv request func start - - \n";

    $select_req_query = "SELECT * from luag_empty_lcv_requests where 
        MGS = '$mgs' and status != 'Fulfilled' and date(Now()) = date(Create_date))";
    
    $select_req_result = mysqli_query($conn, $select_req_query);
    $prev_req_count = mysqli_num_rows($select_req_result);

    if($prev_req_count>0) {
        echo "\n - - Requests from $mgs already exists for empty LCV - - \n";
    } else {

        $insert_req_query = "INSERT into luag_empty_lcv_requests(
            Request_id, MGS, Request_count, Create_user, status
        ) values (
            concat('$mgs', '_', '$count', '_', Now()), '$mgs', $count, 'Scheduling Engine', 'New'
        )";

        $insert_req_result = mysqli_query($conn, $insert_req_query);

        if($insert_req_result) {
            echo "\n - - request for $count empty lcv from $mgs generated successfully";
        } else {
            echo "\n- -  - Unable to generate request - - -\n";
        }


    }

    echo "\n - - generate empty lcv request func end - - \n";

}





function del_from_table($conn, $table_name, $index_column, $index_col_id){
    $del_query = "DELETE from $table_name where $index_column = '$index_col_id'";
    $del_result = mysqli_query($conn, $del_query);

    if($del_result) {
        echo "\n - -- Row deleted successfully - - -\n";
        return true;
    } else {
        echo "\n - -- Unable to delete Row - - -\n";
        return false;
    }
}


function update_table($conn, $table_name, $col_name, $value, $index_col, $index_id) {
    $update_query = "UPDATE $table_name set $col_name = '$value' where $index_col = '$index_id'";
    $update_result = mysqli_query($conn, $update_query);

    return $update_result;
}

function create_priority_queue($data, $parameter) {
    $pq = new SplPriorityQueue();

    foreach($data as $data_key => $data_value) {
        $pq->insert($data[$data_key], $data[$data_key][$parameter]);
    }





}


// todo
// 1. check availability of lcv in allocate_mgs() function
// 2. reduce the count of LCV at mgs in the available_lcv array when mgs allocated to request 
//     -> this will not work as it will not allocte lcv in optimized way or as discussed in scenerios wise
// 3. 



?>





