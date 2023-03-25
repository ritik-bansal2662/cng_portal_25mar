<?php
    include '../../CNG_API/conn.php';
    // $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    $response=array(array());
    if(isset($_POST['mgs'])){
        $mgs = $_POST['mgs'];
        // $sql = "SELECT  distinct(Lcv_Num) Lcv_Num, lcv_status FROM reg_lcv WHERE lcv_status in ('Halt','Transit','', 'not known')" and ;
        $sql = "SELECT * FROM lcv_mgs_mapping WHERE mgs like '%$mgs%'";
        $all_categories = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($all_categories);
        if($num_rows == 0){
            $response['data_available'] = false;
        } else {
            $output="<option value='NA' selected>Select LCV Number</option>";
            $i=0;
            while ($category = mysqli_fetch_array($all_categories,MYSQLI_ASSOC)) {
                $output .= "<option value='".$category['lcv_num']."'>" . $category['lcv_num'] . "</option>";
                $response[$i][0]=$category['lcv_num'];
                $i++;
            }
        }
    }
    // echo $output;
    // print_r($response);
    echo json_encode($response);
?>