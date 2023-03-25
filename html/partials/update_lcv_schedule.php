<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

$dbs=$_POST['mgsid'];
$mgs=$_POST['dbsid'];
$distance=$_POST['distance'];
$time=$_POST['time'];
$lcvid=$_POST['lcvid'];
// $lcvstatus=$_POST['lcvstatus'];
$lcvstatus='Scheduled';

$data=array();

$insert_sql = "insert into luag_schedular_trans (mgs_id, dbs_id, distance_between_stations, lcv_id, lcv_status)
    values ('$dbs', '$mgs', '$distance', '$lcvid', '$lcvstatus')";    
$result = mysqli_query($conn, $insert_sql);
// $num_rows = mysqli_num_rows($result);
// $output = "<option value=''>Select DBS id</option>";
// while($row = $result-> fetch_assoc()) {
//     $output .= "<option value='".$row['Station_Id']."'>".$row['Station_Id']."</option>";
// }

$update_sql="update reg_lcv set lcv_status = '$lcvstatus' where Lcv_Num = '$lcvid'";
$response =  mysqli_query($conn, $update_sql);

if($result && $response){
    $data['error']=true;
    $data['message']='Data inserted';
} else {
    $data['error']=true;
    $data['message'] = 'Error! Data not inserted';
}
echo json_encode($data);

?>