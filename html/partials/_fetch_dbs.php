<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
// $dbs="dbs%";
if(isset($_GET['mgsid'])) {
    $mgs = $_GET['mgsid'];
    $select_sql = "select * from luag_station_master where mgsId = '$mgs'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    $output='';
    if($num_rows>0) {
        $output .= "<option selected value='NA'>Select DBS id</option>";
        while($row = $result-> fetch_assoc()) {
            $output .= "<option value='".$row['Station_Id']."'>".$row['Station_Id']. " - " . $row['Station_Name'] . "</option>";
        }
    } else {
        $output = "<option selected value='NA'>No DBS found</option>";
    }
}
else {
    $output = "<option selected value='NA'>No DBS found</option>";
}

echo $output;

?>