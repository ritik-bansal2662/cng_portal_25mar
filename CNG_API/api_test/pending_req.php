<?php
include '../conn.php';
$select_query = "SELECT * FROM `luag_dbs_request` a, `luag_dbs_to_mgs_routes` b 
    WHERE a.Route_id = b.Route_id and a.Status = 'Pending' order by a.MGS";

$select_result = mysqli_query($conn, $select_query);

$pending_req = array();
while($row = $select_result->fetch_assoc()) {
    var_dump($row);
    $pending_req[$row['Request_id']] = array(
        'MGS' => $row['MGS'],
        'DBS' => $row['DBS'],
        'distance' => $row['Distance'],
        'duration' => $row['Duration'],
        'time_slot' => $row['Time_slot']
    );
}

echo json_encode($pending_req);
echo "\n --- \n";
$first_pending_req = array_key_first($pending_req);
var_dump($first_pending_req);





?>