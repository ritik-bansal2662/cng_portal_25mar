<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

$response = array();
if(isset($_POST['id'])){
    $lcv_num = $_POST['id'];

    $select_sql = "select * from luag_lcv_tracking where lcv_number ='$lcv_num'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    if($num_rows > 0){
        $row = $result-> fetch_assoc();
        $response['error'] = false;
        $response['latitude'] = $row['latitude'];
        $response['longitude'] = $row['longitude'];
    } else {
        $response['error'] = true;
    }
    // echo $row['Latitude_Longitude'];
    echo json_encode($response);
}

?>