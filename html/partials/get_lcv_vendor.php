<?php

// this api is used by:
// 1. lcv_status.js
// 2.

include '../../CNG_API/conn.php';

// to return a json value
header('Content-Type: application/json; charset=utf-8');

$response = array();

if(isset($_GET['lcv_num'])) {
    $lcv_num = $_GET['lcv_num'];
    $select_query = "SELECT Lcv_Registered_To from reg_lcv where Lcv_Num = '$lcv_num'";
    $result = mysqli_query($conn, $select_query);
    $num_rows = mysqli_num_rows($result);

    $temp_array = array();
    if($num_rows > 0) {
        while($row = $result-> fetch_assoc()) {
            $response['vendor_name'] = $row['Lcv_Registered_To'];
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'No data found for selected LCV.';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'No LCV Selected.';
}


echo json_encode($response)

?>

