<?php 
include '../../CNG_API/conn.php';
// $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

$select_sql = "SELECT * from luag_station_master where Station_type = 'Daughter Booster Station'";
$result = mysqli_query($conn, $select_sql);
$num_rows = mysqli_num_rows($result);
$output = "<option value='NA'>Select DBS id</option>";
while($row = $result-> fetch_assoc()) {
    $output .= "<option data-coordinates='". $row['Latitude_Longitude'] ."' value='".$row['Station_Id']."'>".$row['Station_Id']."</option>";
}

echo $output;

?>