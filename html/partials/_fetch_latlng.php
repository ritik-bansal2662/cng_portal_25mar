<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

if(isset($_POST['id'])){
    $station_id = $_POST['id'];

    $select_sql = "select Latitude_Longitude from luag_station_master where Station_Id ='$station_id'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    $row = $result-> fetch_assoc();
    echo $row['Latitude_Longitude'];
}

?>