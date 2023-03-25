<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

if(isset($_POST['table'])) {
    $table_name=$_POST['table'];
    $select_sql = "select distinct(Lcv_registered_To) from $table_name";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    $output='';
    if($num_rows > 0) {
        $output .= "<option value='NA'>Select Organization</option>";
        while($row = $result-> fetch_assoc()) {
            $output .= "<option value='".$row['Lcv_registered_To']."'>".$row['Lcv_registered_To']."</option>";
        }
    } else {
        $output = "<option value='NA'>No Organization Found</option>";
    }
}
else {
    $output = "<option value='NA'>-------------------</option>";
}

echo $output;

?>