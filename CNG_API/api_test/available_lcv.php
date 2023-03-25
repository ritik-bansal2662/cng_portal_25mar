<?php

include '../conn.php';
header('Content-Type: application/json; charset=utf-8');

$arr = array(1,2,3,4);

function lcv($conn) {
  echo "\n - -  array - -  \n";
  global $arr;
  echo json_encode($arr);
  echo "\n - -  array end - -  \n";
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
  return $lcv_at_mgs;
}
$available_lcv = lcv($conn);
// echo "\n\n - - - -  count - - - - -\n\n";
// echo $available_lcv['mgs123']['count'];

echo "\n available lcv \n";
echo json_encode($available_lcv);

echo "\n lcv priority queue \n";

    foreach($available_lcv as $mgs_id => $lcv_details) {
      echo "\n- - MGS id - - $mgs_id\n";
      echo json_encode($lcv_details);
      echo "\n - - - \n";
      if($mgs_id == '0') continue;
      $lcv_priority_queue = new SplPriorityQueue();

      foreach($lcv_details as $index => $lcv){
        echo "\n index \n";
        echo json_encode($index);
        echo "\n";
        echo json_encode($lcv);
        if($index == 'count') {continue;}

        $lcv_priority_queue->insert($lcv['Notification_LCV'], $lcv['flag']);
        echo "\n- notif lcv - -",$lcv_details[$index]['Notification_LCV'] ,"\n - - \n";
      }
      echo "\n", $mgs_id , "\n";
      echo "\n - - PQ - -\n";
      var_dump($lcv_priority_queue);
    }

?>