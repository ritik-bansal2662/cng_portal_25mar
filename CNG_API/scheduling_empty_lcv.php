<?php


include 'conn.php';
header('Content-Type: application/json; charset=utf-8');

// this engine is developed to schedule empty LCVs to MGS according to requests and time taken by LCV to reach destionation


// flow of engine
//
// 1. get the MGS with empty slots
// 2. get LCV available at stage 6 and 5
// 3. allocate these MGS with lowest time DBS(if LCV is available)
// 4. allocate LCV of shortest time DBS to each MGS
// 5. if none MGS have empty slot and LCV are available at DBS
//  5.1 allocate to the greatest time MGS


{

  echo "\n - - - scheduling of empty LCV start - - \n";

  
  // first we will get the MGS with empty slot at stage 2
  // $mgs_with_empty_stage_two_slot = get_mgs_with_slot($conn, 2);

  // get the MGS with empty slot at stage 1
  $mgs_with_empty_stage_one_slot = get_mgs_with_slot($conn, 1);


  // get all LCV at stage 6 and which are not scheduled to go to MGS
  $lcv_at_stage_six = get_lcv_at_dbs($conn, 6);
  echo "\n - - LCV at stage 6 - - \n";
  echo json_encode($lcv_at_stage_six), "\n";

  // get all LCV at stage 6 and which are not scheduled to go to MGS
  // $lcv_at_stage_five = get_lcv_at_dbs($conn, 5);
  // echo "\n - - LCV ata stage 5 - - \n";
  // echo json_encode($lcv_at_stage_five), "\n";


  // $mgs_with_route_details = get_routes($conn, $mgs_with_empty_stage_two_slot);
  // echo "\n - - mgs with routes(empty stage 2) - - \n";
  // echo json_encode($mgs_with_route_details), "\n";

  // if(count($mgs_with_route_details) > 0 && ($lcv_at_stage_six['lcv_count'] > 0 || $lcv_at_stage_five['lcv_count'] > 0) ) {
  // if(count($mgs_with_route_details) > 0 && $lcv_at_stage_six['lcv_count'] > 0 ) {
  //   echo "\n - - scheduling for empty slot 2 - - \n";

  //   schedule($conn, 2);
     
  //   echo "\n - - after scheduling of MGS with empty stage 2 slots - - \n";
  //   echo "\n - - lcv at stage 6 - - \n";
  //   echo json_encode($lcv_at_stage_six);
  //   echo "\n - - lcv at stage 5 - - \n";
  //   echo json_encode($lcv_at_stage_five);
  // }

  // scheduling LCV for MGS with empty stage 1
  
  echo "\n - - \n - - \n";
  echo "\n - - scheduling LCV for mgs with empty stage 1 - - \n";

  $mgs_with_route_details = get_routes($conn, $mgs_with_empty_stage_one_slot);
  echo "\n - - mgs with routes(empty stage 1) - - \n";
  echo json_encode($mgs_with_route_details), "\n";


  // if(count($mgs_with_route_details) > 0 && ($lcv_at_stage_six['lcv_count'] > 0 || $lcv_at_stage_five['lcv_count'] > 0) ) {
  if(count($mgs_with_route_details) > 0 && $lcv_at_stage_six['lcv_count'] > 0 ) {
    echo "\n - - scheduling lcv for empty slot 1  - --\n";

    schedule($conn, 1);
    
    echo "\n - - after scheduling of MGS with empty stage 1 slots - - \n";
    echo "\n - - lcv at stage 6 - - \n";
    echo json_encode($lcv_at_stage_six);
    // echo "\n - - lcv at stage 5 - - \n";
    // echo json_encode($lcv_at_stage_five);
  }
  
  
  echo "\n - - number of LCV left for scheduling (stage 6): ", $lcv_at_stage_six['lcv_count'], " - - \n";
  // echo "\n - - number of LCV left for scheduling (stage 5): ", $lcv_at_stage_five['lcv_count'], " - - \n";

  // check if there are LCV at stage 6
  if($lcv_at_stage_six['lcv_count'] > 0) {

    // allocate according to greatest time

    echo "\n - - lcv still left for allocation - - \n";

    $mgs = get_all_mgs($conn);

    echo "\n - - All MGS - -\n";
    echo json_encode($mgs), "\n";

    $mgs_with_route_details = get_routes($conn, $mgs);
    echo "\n - - MGS with routes - - \n";
    echo json_encode($mgs_with_route_details), "\n";
    scheduling_remaining_lcv($conn);

  }







  echo "\n - - - scheduling of empty LCV end - - \n";

  echo 1;
}


function check_requests($conn) {
  $requests = array();
  echo "\n - - check req function start - - \n";

  $request_select_query = "SELECT * from luag_dbs_request where Status = 'New Request' or Status = 'Previous Pending'";
  $request_result = mysqli_query($conn, $request_select_query);
  $request_count = mysqli_num_rows($request_result);
  $requests['total_requests'] = $request_count;

  if($request_count > 0 ) {
      while($req_row = $request_result->fetch_assoc()) {
          $requests[$req_row['Request_id']] = array(
              'reading_id' => $req_row['Reading_id'], 
              'mgs' => $req_row['MGS'], 
              'dbs' => $req_row['DBS']
          );
      }
  } else {
      echo "\n zero requests found. \n";
  }

  echo "\n - - check req function end - - \n";

  return $requests;

}


function get_available_lcv_at_mgs($conn) {

    echo "\n - - -  get available lcv at MGS function - - \n";
    $lcv_select_query = "WITH ranked_lcv AS (
        SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
        FROM notification AS m
      )
      SELECT * FROM ranked_lcv 
        WHERE rn = 1 and 
        (Notification_DBS is NULL or Notification_DBS = '')"; 
    
    $lcv_result = mysqli_query($conn, $lcv_select_query);
    $total_lcv_available = mysqli_num_rows($lcv_result);
    echo "\n - - - Total LCV available : $total_lcv_available - - - - \n";

    $lcv_at_mgs = array(array());
    $lcv_at_mgs['total_lcv_available'] = $total_lcv_available;
    
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


    echo "\n - - -  LCV at MGS - - \n";
    echo json_encode($lcv_at_mgs);

    echo "\n - - -  get available lcv function end - - \n";

    return $lcv_at_mgs;

}


function get_scheduled_lcv($conn) {
  echo "\n - - -get scheduled lcv func start";

  $select_lcv_query = "SELECT * from luag_empty_lcv_scheduling where status = 'Scheduled'";
  $scheduled_lcv_result = mysqli_query($conn, $select_lcv_query);
  $scheduled_lcv_count = mysqli_num_rows($scheduled_lcv_result);

  echo "\n - - Scheduled LCV count : $scheduled_lcv_count - - \n";

  $scheduled_lcv = array();
  $scheduled_lcv['total_scheduled_lcv'] = $scheduled_lcv_count;

  while($lcv_row = $scheduled_lcv_result->fetch_assoc()) {
    echo "\n- -- - scheduled lcv row  -- -- \n";
    echo json_encode($lcv_row);
    
    if( array_key_exists($lcv_row['Notification_MGS'],$scheduled_lcv)) {
      array_push($scheduled_lcv[$lcv_row['Notification_MGS']],$lcv_row);
      $scheduled_lcv[$lcv_row['Notification_MGS']]['count'] += 1;
    } else {
      $scheduled_lcv[$lcv_row['Notification_MGS']][0] = $lcv_row;
      $scheduled_lcv[$lcv_row['Notification_MGS']]['count'] = 1;
    }
  }

  echo "\n - - scheduled lcvs -  -\n";
  echo json_encode($scheduled_lcv), "\n";

  echo "\n - - -get scheduled lcv func end";

  return $scheduled_lcv;
}


function get_lcv_at_dbs($conn, $stage) {
  echo "\n - - get lcv in stage: $stage at dbs func start - - \n";

  // this query will select all LCVs which are in stage = $stage and at DBS and are not scheduled yet
  // so that we will not encounter the problem of scheduling same LCV more than once at a time

  $lcv_select_query = "WITH ranked_lcv AS (
    SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
    FROM notification AS m
  )
  SELECT * FROM ranked_lcv 
    WHERE rn = 1 and flag = '$stage' and Notification_LCV not in (
      SELECT LCV_num from luag_empty_lcv_scheduling where status = 'Scheduled'
    )"; 

  $lcv_result = mysqli_query($conn, $lcv_select_query);
  $total_lcv_available = mysqli_num_rows($lcv_result);
  echo "\n - - - Total LCV available : $total_lcv_available - - - - \n";

  $lcv_at_dbs = array();
  $lcv_at_dbs['lcv_count'] = $total_lcv_available;

  while($lcv_row = $lcv_result->fetch_assoc()) {
      echo "\n- -- - lcv row  -- -- \n";
      echo json_encode($lcv_row);
      // array_push($lcv_at_dbs, $lcv_row);

      $lcv_row['allocated'] = false;

      if( array_key_exists($lcv_row['Notification_DBS'],$lcv_at_dbs)) {
        array_push($lcv_at_dbs[$lcv_row['Notification_DBS']],$lcv_row);
        $lcv_at_dbs[$lcv_row['Notification_DBS']]['count'] += 1;
      } else {
        $lcv_at_dbs[$lcv_row['Notification_DBS']][0] = $lcv_row;
        $lcv_at_dbs[$lcv_row['Notification_DBS']]['count'] = 1;
      }
  }

  echo "\n - - get lcv at dbs func end - - \n";

  return $lcv_at_dbs;
}


function get_mgs_with_slot($conn, $stage) {
    echo "\n - - get mgs with slot $stage func start - - \n";

    // this query will select the mgs at which stage 2 is empty
    // first it will select all mgs which have lcv at stage 2
    // then it will select MGS to which LCV have been scheduled
    // then it will select MGS which are not in the above two selects
    // this will be the final list
    $select_mgs_query = "SELECT * from luag_station_master 
        where station_type = 'Mother Gas Station' 
        and station_id not in (WITH ranked_lcv AS (
        SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
        FROM notification AS m
        ) SELECT Notification_MGS FROM ranked_lcv 
        WHERE rn = 1 and flag = '$stage')
        and station_id not in (
          select MGS from luag_empty_lcv_scheduling 
            where status = 'Scheduled' and (MGS_slot = '$stage' or MGS_slot = '0') group by (MGS)
        )";

    $mgs_result = mysqli_query($conn, $select_mgs_query);
    $mgs_count = mysqli_num_rows($mgs_result);
    echo "\n - - total mgs count: $mgs_count - - -\n";

    $mgs_with_empty_slot = array();

    while($mgs_row = $mgs_result->fetch_assoc()) {
      // array_push($mgs_with_empty_slot, $mgs_row['station_id']);
      echo "\n - - mgs row - - \n", json_encode($mgs_row), "\n";
      $mgs_with_empty_slot[$mgs_row['Station_Id']] = array();
    }

    echo "\n - - get mgs with slot $stage func end - - -\n";

    return $mgs_with_empty_slot;
}


function get_routes($conn, $mgs_arr) {

  echo "\n - - get routes func start - - \n";
  echo "\n - - mgs arr - - \n", json_encode($mgs_arr), "\n";

  $mgs_route_details = array();

  foreach($mgs_arr as $mgs => $arr) {
    echo "\n - foreach loop : $mgs \n";
    $route_select_query = "SELECT * from luag_dbs_to_mgs_routes where MGS = '$mgs'";
    $route_result = mysqli_query($conn, $route_select_query);
    $route_count = mysqli_num_rows($route_result);

    if($route_count > 0) {

      while($route_row = $route_result->fetch_assoc()) {
        // $mgs_arr[$route_row['DBS']] = $route_row['Duration'];
        echo "\n - - route row - - \n", json_encode($route_row), "\n";

        if( array_key_exists($route_row['DBS'],$arr)) {

          if( $route_row['Duration'] < $arr[$route_row['DBS']]) {
            $arr[$route_row['DBS']] = array ( $route_row['Duration'], $route_row['Route_id'] );
          }

          // array_push($arr[$route_row['DBS']], $route_row['Duration']);
          // $arr[$route_row['DBS']]['count'] += 1;
        } else {
          $arr[$route_row['DBS']] = array ( $route_row['Duration'], $route_row['Route_id'] );
          // $arr[$route_row['DBS']]['count'] = 1;
        }
      }

      echo "\n - - arr unsorted - - \n", json_encode($arr), "\n";

      asort($arr);
      echo "\n - -arr sorted- - \n", json_encode($arr), "\n";
      $mgs_route_details[$mgs] = $arr;

    } else {
      echo "\n - - no route found for mgs : $mgs  - - \n";
    }

  }

  echo "\n - -arr sorted- - \n",json_encode($mgs_route_details), "\n";
  asort($mgs_route_details);
  echo  "\n - -arr sorted- - \n", json_encode($mgs_route_details), "\n";

  echo "\n - - get routes func end - -\n";

  return $mgs_route_details;
}




function schedule($conn, $stage) {
  echo "\n - - schedule func start - - \n";
  global $mgs_with_route_details;
  global $lcv_at_stage_six;
  // global $lcv_at_stage_five;

  // $flag = 0;
  $mgs_arr = $mgs_with_route_details;
  $lcv_arr = $lcv_at_stage_six;


  foreach($mgs_arr as $mgs => $route_details) {
    foreach($route_details as $dbs => $route_arr) {

      // if($lcv_arr['lcv_count'] == 0){ 
      //   if($flag == 1) break;
      //   // if all LCV at stage 6 have been allocated to MGS and still some MGS are left
      //   // then we will get LCVs at stage 5 and allocate them to MGS
      //   // $lcv_arr = get_lcv_at_dbs($conn, 5);
      //   $lcv_arr = $lcv_at_stage_five;
      //   $flag = 1;
      // }

      if($lcv_arr['lcv_count'] > 0 && array_key_exists($dbs, $lcv_arr) && $lcv_arr[$dbs]['count'] > 0) {

        $route_id = $route_arr[1];
        $allocate_result = allocate_lcv_to_mgs($conn, $mgs, $lcv_arr[$dbs], $dbs, $route_id, $stage);
        echo "\n - - allocation result - -\n", json_encode($allocate_result), "\n";

        if($allocate_result[0] == true) {
          $index = $allocate_result[1];
          $lcv_arr[$dbs][$index]['allocated'] = true;
          $lcv_arr[$dbs]['count'] -= 1;
          $lcv_arr['lcv_count'] -= 1;

          echo "\n - - after allocation -- \n";
          echo json_encode($lcv_arr[$dbs]), "\n";

          // if($lcv_arr['lcv_count'] == 0) {
          //   if($flag == 0) {
          //     $lcv_at_stage_six = $lcv_arr;
          //     $lcv_arr = $lcv_at_stage_five;
          //     $flag = 1;
          //   } else if ($flag == 1) {
          //     $lcv_at_stage_five = $lcv_arr;
          //   }
          // }
          break;
        }
        
      }

      
    }

    // if($lcv_arr['lcv_count'] == 0 && $flag == 1) break;
    if($lcv_arr['lcv_count'] == 0){
      break;
    }

  }

  // if($flag == 0) {
  //   echo "\n - - flag 0 - - \n";
  //   $lcv_at_stage_six = $lcv_arr;
  // } else if($flag == 1) {
  //   echo "\n - - flag 1 - - \n";
  //   $lcv_at_stage_five = $lcv_arr;
  // }

  $lcv_at_stage_six = $lcv_arr;

  echo "\n - - schedule func end - - \n";
}


function allocate_lcv_to_mgs($conn, $mgs, $lcv_arr, $dbs, $route_id, $stage) {

  echo "\n - - - allocate lcv to mgs func start -- - - \n";

  $response = array();
  $lcv_num='';

  foreach($lcv_arr as $index => $lcv_data) {

    echo "\n - - lcv index: $index - - \n";
    
    if($index == 'count') continue;

    echo "\n - - lcv index: $index - - \n";

    if($lcv_data['allocated'] == false) { //if that lcv is not already allocated 
      
      $lcv_num = $lcv_data['Notification_LCV'];
      $insert_query = "INSERT into luag_empty_lcv_scheduling (LCV_num, MGS, DBS, Route_id, status, MGS_slot, create_user)
        values ('$lcv_num', '$mgs', '$dbs', '$route_id', 'Scheduled', '$stage', 'Scheduling Engine')";
      
      $insert_result = mysqli_query($conn, $insert_query);

      if($insert_result) {
        echo "\n - - LCV($lcv_num) scheduled successfully to MGS($mgs) from DBS($dbs) - - \n";
        
        array_push($response, true, $index);
        echo "\n - - -allocate lcv to mgs func end -- - - \n";
        return $response;
      }

      echo "\n - -1 unable to schedule LCV($lcv_num) to MGS($mgs) from DBS($dbs) due to some SQL error - - \n";
    }
    echo "\n - -2 unable to schedule LCV($lcv_num) to MGS($mgs) from DBS($dbs) as this LCV is already scheduled - - \n";
  }

  echo "\n - -3 unable to schedule any LCV to MGS($mgs) from DBS($dbs) as no LCV available - - \n";

  array_push($response, false); 
  echo "\n - - -allocate lcv to mgs func end -- - - \n";
  return $response;
}


function get_all_mgs($conn) {
    echo "\n - - get all mgs func start - - \n";

    $select_mgs_query = "SELECT * from luag_station_master where station_type = 'Mother Gas Station'"; 

    $mgs_result = mysqli_query($conn, $select_mgs_query);
    $mgs_count = mysqli_num_rows($mgs_result);
    echo "\n - - Number of MGS: $mgs_count - - -\n";

    $mgs = array();

    while($mgs_row = $mgs_result->fetch_assoc()) {
      echo "\n - - mgs row - - \n", json_encode($mgs_row), "\n";
      $mgs[$mgs_row['Station_Id']] = array();
    }

    echo "\n - - get all mgs func end - - -\n";

    return $mgs;
}


function scheduling_remaining_lcv($conn){

  echo "\n - - scheduling remaining lcv func start - -\n";

  global $mgs_with_route_details;
  global $lcv_at_stage_six;

  echo "\n - - mgs details - - \n";
  echo json_encode($mgs_with_route_details), "\n";

  // sorting array in descending order according to values
  arsort($mgs_with_route_details);

  echo "\n - - mgs details sorted in descending order - - \n";
  echo json_encode($mgs_with_route_details), "\n";

  $total_lcv = $lcv_at_stage_six['lcv_count'];
  echo "\n - - total lcv : $total_lcv - - \n";

  $total_mgs = count($mgs_with_route_details);
  echo "\n - - total mgs : $total_mgs -- - \n";

  $lcv_to_each = intdiv($total_lcv,$total_mgs);
  $remainder = $total_lcv % $total_mgs;
  echo "\n - - lcv to each : $lcv_to_each - - \n";
  echo "\n - - remainder : $remainder - - \n";

  if($lcv_to_each > 0) {
    foreach($mgs_with_route_details as $mgs => $route_details) {
      echo "\n - - $mgs -  - \n";
      
      scheduling_acc_loop($conn, $lcv_to_each, $mgs, $route_details);

    }
  }

  $mgs = array_key_first($mgs_with_route_details);
  $route_details = $mgs_with_route_details[$mgs];

  echo "\n - - - farthest MGS = $mgs - - -\n";

  // this is to schedule the remaining LCVs to the farthest MGS
  scheduling_acc_loop($conn, $remainder, $mgs, $route_details);

  echo "\n - - scheduling remaining lcv func end - -\n";

}


function scheduling_acc_loop($conn, $count, $mgs, $route_details) {
  echo "\n - - scheduling acc loop func start- - \n";

  global $lcv_at_stage_six;

  while($count > 0) {
    echo "\n - - lcv to each : $count - -\n";

    foreach($route_details as $dbs => $route_arr) {

      echo "\n - - dbs : $dbs -- - \n";

      // if($lcv_at_stage_six['lcv_count'] == 0){ 
      //   if($flag == 1) break;
      //   // if all LCV at stage 6 have been allocated to MGS and still some MGS are left
      //   // then we will get LCVs at stage 5 and allocate them to MGS
      //   // $lcv_at_stage_six = get_lcv_at_dbs($conn, 5);
      //   $lcv_at_stage_six = $lcv_at_stage_five;
      //   $flag = 1;
      // }

      echo "lcv_at_stage_six[lcv_count] : " , $lcv_at_stage_six['lcv_count'], " - - - \n";

      if($lcv_at_stage_six['lcv_count'] > 0 && array_key_exists($dbs, $lcv_at_stage_six) && $lcv_at_stage_six[$dbs]['count'] > 0) {

        $route_id = $route_arr[1];
        $allocate_result = allocate_lcv_to_mgs($conn, $mgs, $lcv_at_stage_six[$dbs], $dbs, $route_id, 0);
        echo "\n - - allocation result - -\n", json_encode($allocate_result), "\n";

        if($allocate_result[0] == true) {
          $index = $allocate_result[1];
          $lcv_at_stage_six[$dbs][$index]['allocated'] = true;
          $lcv_at_stage_six[$dbs]['count'] -= 1;
          $lcv_at_stage_six['lcv_count'] -= 1;

          echo "\n - - after allocation -- \n";
          echo json_encode($lcv_at_stage_six[$dbs]), "\n";

          // if($lcv_at_stage_six['lcv_count'] == 0) {
          //   if($flag == 0) {
          //     $lcv_at_stage_six = $lcv_at_stage_six;
          //     $lcv_at_stage_six = $lcv_at_stage_five;
          //     $flag = 1;
          //   } else if ($flag == 1) {
          //     $lcv_at_stage_five = $lcv_at_stage_six;
          //   }
          // }
          break;
        }
        
      }

      
    }


    $count -= 1;
  }

  echo "\n - - scheduling acc loop func end - - \n";
}





?>
