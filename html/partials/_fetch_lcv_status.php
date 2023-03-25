<?php
    include '../../CNG_API/conn.php';
    $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    if(isset($_POST['lcvid'])){
        $lcv_num=$_POST['lcvid'];
        $select_sql = "SELECT * FROM  luag_transaction_master_dbs_station WHERE lcv_id = '$lcv_num'";
        $result = mysqli_query($conn, $select_sql);
        $output = 'Free Zone';
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // Stage
            // 1. LCV Entered the premises at MGS
            // 2. Before Filling
            // 3. After Filling
            // 4. LCV Entered the premises at DBS
            // 5. Before Emptying
            // 6. After Emptying
            // Stages 2 and 5 are safe zone others Free Zone.
            $stage = $row['lcv_from_mgs_to_dbs'];
            if($stage == 2 || $stage == 5){
                $output = $stage . '. Safe Zone';
            } else {
                $output = $stage . '. Free Zone';
            }
        }
    } else {
        $output='';
    }
    echo $output;
?>