<?php

// this api is used by: 
// 1. mgs_dbs_route.js
// 2. 

include "conn.php";

// to return a json value
header('Content-Type: application/json; charset=utf-8');

$response = array();

session_start();
if (isTheseParametersAvailable(array(
    'mgs_id', 'dbs_id', 'route_id', 'time_slot', 
    'distance', 'duration'
    ))) {
    $mgs = $_POST['mgs_id'];
    $dbs = $_POST['dbs_id'];
    $route_id = $_POST['route_id'];
    $time_slot = $_POST['time_slot'];
    $distance = $_POST['distance'];
    $duration = $_POST['duration'];
    $create_user = $_SESSION['user_id'];

    if(isset($_POST['start_coordinates'])){
        $start_coord = $_POST['start_coordinates'];
    } else {
        $start_coord = '';
    }

    if(isset($_POST['via_coordinates'])){
        $via_coord = $_POST['via_coordinates'];
    } else {
        $via_coord = '';
    }

    if(isset($_POST['end_coordinates'])){
        $end_coord = $_POST['end_coordinates'];
    } else {
        $end_coord = '';
    }

    if(isset($_POST['route_description'])){
        $route_description = $_POST['route_description'];
    } else {
        $route_description = '';
    }

    // echo $route_id, ' - ', $dbs, ' - ', $mgs, ' - ', $start_coord, ' - ', $via_coord, ' - ',
    //     $end_coord, ' - ', $distance, ' - ', $duration, ' - ', $time_slot, ' - ', $route_description, ' - ', 'ritik';

    $check_sql = "SELECT * from luag_dbs_to_mgs_routes where DBS = '$dbs' and MGS = '$mgs' and Route_id = '$route_id' and Time_slot = '$time_slot'";
    $check_result = mysqli_query($conn, $check_sql);
    $check_num_rows = mysqli_num_rows($check_result);

    $route_sql = "SELECT * from luag_dbs_to_mgs_routes where Route_id = '$route_id'";
    $route_result = mysqli_query($conn, $route_sql);
    $route_num_rows = mysqli_num_rows($route_result);

    if($check_num_rows > 0 ) {
        $response['error'] = true;
        $response['message'] = 'Route already exists.';
    } else if ($route_num_rows > 0) {
        $response['error'] = true;
        $response['message'] = 'Route ID already exists.';
    } else {
        $insert_sql = "INSERT INTO luag_dbs_to_mgs_routes(Route_id, 
        DBS, MGS, Start_coordinates, Via_Coordinates, End_coordinates,
        Distance, Duration, Time_Slot, Route_description, Create_user) 
        VALUES ('$route_id', '$dbs', '$mgs', '$start_coord', '$via_coord', 
        '$end_coord', '$distance', '$duration', '$time_slot', '$route_description', 'ritik')";

        $insert_result = mysqli_query($conn, $insert_sql);

        if($insert_result) {
            $response['error'] = false;
            $response['message'] = 'Route Details Inserted Successfully!';
        } else {
            $response['error'] = true;
            $response['message'] = 'Unable to insert Route details at this moment.';
        }
    }
}
else {
    $response['error'] = true;
    $response['message'] = 'Enter all Mandatory fields';
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
