<?php
    include '../../CNG_API/conn.php';
    // $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    $response=array(array());
    $lcv_details = array(array());
    // if(isset($_POST['station_id'])){
    //     $station_id = $_POST['station_id'];
        // $sql = "SELECT  distinct(Lcv_Num) Lcv_Num, lcv_status FROM reg_lcv WHERE lcv_status in ('Halt','Transit','', 'not known')" and ;

        // $select_mgs_sql = "SELECT mgs FROM lcv_mgs_mapping group by mgs";
        $select_mgs_sql = "SELECT * FROM lcv_mgs_mapping";
        $result_mgs = mysqli_query($conn, $select_mgs_sql);
        $num_mgs_rows = mysqli_num_rows($result_mgs);
        if($num_mgs_rows == 0) {
            $response['data_available'] = false;
        } else {
            $response['data_available'] = true;

            $select_lcv_sql = "SELECT * FROM reg_lcv";
            $result_lcv = mysqli_query($conn, $select_lcv_sql);
            $num_lcv_rows = mysqli_num_rows($result_lcv);
            while($lcv_row = mysqli_fetch_array($result_lcv, MYSQLI_ASSOC)) {
                $lcv_details[$lcv_row['Lcv_Num']] = $lcv_row['Lcv_Registered_To'];
            }
            $i=0;
            while($row = mysqli_fetch_array($result_mgs, MYSQLI_ASSOC)) {
                $response[$i][0] = $row['lcv_num'];
                $response[$i][1] = $lcv_details[$row['lcv_num']];
                $response[$i][2] = $row['mgs'];
                $i++;
            }

            // echo count($response);
            // for($j=0; $j<count($response); $j++) {
            //     // print_r($response);
            //     $mgs = $response[$j][0];
                
            //     $select_lcv_sql = "SELECT lcv_num from lcv_mgs_mapping where mgs = '$mgs'";
            //     $result_lcv = mysqli_query($conn, $select_lcv_sql);
            //     $lcv_list = '';
            //     while($lcv_row = mysqli_fetch_array($result_lcv, MYSQLI_ASSOC)) {
            //         $lcv_list .= $lcv_row['lcv_num'] . ', ';
            //     }
            //     $response[$j][1] = $lcv_list;
            // }
        }
    // }
    // echo $output;
    // print_r($response);
    echo json_encode($response);
?>