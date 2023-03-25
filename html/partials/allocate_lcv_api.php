<?php 

include '../../CNG_API/conn.php';

if(isset($_POST['lcv'])) {
    $lcv = $_POST['lcv'];
    $mgs = $_POST['lcv_mgs'];
    // $org = $_POST['organization_id'];
    $select_sql = "select * from lcv_mgs_mapping where lcv_num = '$lcv'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);

    if($num_rows > 0) {
        $update_sql = "update lcv_mgs_mapping set mgs = '$mgs' where lcv_num = '$lcv'";
        $update_result = mysqli_query($conn, $update_sql);

        if($update_result) {
            $response['error']=false;
            $response['message'] = "LCV Mapping updataion Successful.";
        }
    } else {
        $sql_query = "INSERT INTO lcv_mgs_mapping (lcv_num, mgs) values ('$lcv','$mgs')";
        $insert_result = mysqli_query($conn, $sql_query);

        if($insert_result) {
            $response['error']=false;
            $response['message'] = "LCV Mapped Successfully.";
        } else {
            $response['error']=true;
            $response['message'] = "Unable to map LCV at this moment.";
        }
    }

} else {
    $response['error'] = true;
    $response['message'] = "Enter all Fields";
}


echo json_encode($response)












?>