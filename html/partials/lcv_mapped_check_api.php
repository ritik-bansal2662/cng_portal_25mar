<?php 

include '../../CNG_API/conn.php';

if(isset($_POST['lcv'])) {
    $lcv = $_POST['lcv'];
    $select_sql = "select * from lcv_mgs_mapping where lcv_num = '$lcv'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    if($num_rows > 0) {
        $row = $result-> fetch_assoc();
        $response['isMapped'] = true;
        $response['mgs'] = $row['mgs'];
        $response['message'] = "LCV already mapped to ". $row['mgs']. '. Would you like to update?';
    } else {
        $response['isMapped'] = false;
        $response['mgs'] = 'NA';
        $response['message'] = "LCV is not mapped to any MGS.";
    }
}

echo json_encode($response)












?>