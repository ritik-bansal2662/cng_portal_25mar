<?php
    include '../../CNG_API/conn.php';
    // $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    $response=array();
    if(isset($_POST['station_id'])){
        $station_id = $_POST['station_id'];
        // $sql = "SELECT  distinct(Lcv_Num) Lcv_Num, lcv_status FROM reg_lcv WHERE lcv_status in ('Halt','Transit','', 'not known')" and ;
        $select_sql = "SELECT * FROM luag_transaction_dbs_dispenser_cascade where station_id = '$station_id' order by update_date desc limit 1";
        $result = mysqli_query($conn, $select_sql);
        $num_rows = mysqli_num_rows($result);
        if($num_rows == 0) {
            $response['data_available'] = false;
            $response['mass_of_gas'] = 'Not Available';
        } else {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $response['data_available'] = true;
            $response['mass_of_gas'] = $row['mass_of_gas'];
        }
    }
    // echo $output;
    // print_r($response);
    echo json_encode($response);
?>