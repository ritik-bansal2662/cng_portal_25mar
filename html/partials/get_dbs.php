<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
// $dbs="dbs%";
if(isset($_GET['mgsid'])) {
    $mgs = $_GET['mgsid'];
    $select_sql = "select Station_Id from luag_station_master where mgsId = '$mgs'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    $output='';
    if($num_rows>0) {
        // $output .= "<option value='NA'>Select DBS id</option>";
        while($row = $result-> fetch_assoc()) {
            // $output .= "<option value='".$row['Station_Id']."'>".$row['Station_Id']."</option>";
            $output .= '<div class="checkbox_group"><input type="checkbox" name="lcv_mgs" class="mgs_checkbox" value="'. $row['Station_Id'] .'"><label for="vehicle1">'. $row['Station_Id'] .'</label></div>';
        }
    } else {
        // $output = "No DBS found";
    }
}
else {
    // $output = "No DBS found";
}

echo $output;

?>