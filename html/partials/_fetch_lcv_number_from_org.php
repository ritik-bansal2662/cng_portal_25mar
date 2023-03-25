<?php 

// this api has been used by :
// 1. allocate_lcv.js
// 2. 

include '../../CNG_API/conn.php';
// $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

if(isset($_POST['org']) && isset($_POST['table'])){
    $org=$_POST['org'];
    $table='';
    if($_POST['table']=='general') {
        $table='reg_lcv';
    }
    else if($_POST['table']=='cascade') {
        $table='reg_instrument_lcv';
    }
    $select_sql = "select Lcv_Num from $table where Lcv_registered_To = '$org'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    $output='';
    if($num_rows > 0) {
        $output .= "<option value='NA'>Select Lcv Number</option>";
        while($row = $result-> fetch_assoc()) {
            $output .= "<option value='".$row['Lcv_Num']."'>".$row['Lcv_Num']."</option>";
        }
    } else {
        $output = "<option value='NA'>No LCV found for selected organization</option>";
    }
}
else {
    $output = "<option value='NA'>No LCV Found</option>";
}

echo $output;

?>