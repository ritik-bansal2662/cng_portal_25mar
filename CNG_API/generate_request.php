<?php

include 'conn.php';
$response = array();


if(isTheseParametersAvailable(array(
    'reading_id', 'dbs'
))) {
    $dbs = $_POST['dbs'];
    $reading_id = $_POST['reading_id'];

    $check_request_query = "SELECT * from luag_dbs_request where DBS = '$dbs' and STATUS = 'Pending'";
    $check_result = mysqli_query($conn, $check_request_query);
    $request_count = mysqli_num_rows($check_result);

    if($request_count == 0) {

        // insert data in request table

        $req_insert_sql = "INSERT into luag_dbs_request(Request_id, Reading_id, DBS, STATUS, Operator_id )
            values('','$reading_id', '$dbs', 'Pending', 'Ritik')";
        $req_insert_result = mysqli_query($conn, $req_insert_sql);

        if($req_insert_result) {
            
            scheduling($conn, $selected_mgs, $dbs);

        } else {
            echo 'unable to generate request';
        }

    } else {
        $resposne['error'] = true;
        $response['message'] = "Reques already genetrated for '$dbs'.";
    }
} else {
    $resposne['error'] = true;
    $response['message'] = 'All Fields not set.';
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