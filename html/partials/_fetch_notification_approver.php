<?php 
    include '../../CNG_API/conn.php';
    $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    $adm="adm%";
    $man="man%";

    $select_sql= "select Emp_num from luag_employee_registration where Emp_num like '$adm' or Emp_num like '$man'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    // echo "Number of rows : ";
    // echo $num_rows;
    $output="<option value='0'>Select Notification Approver Id</option>";
    while($row = $result-> fetch_assoc()) {
        $output .= "<option value='".$row['Emp_num']."'>".$row['Emp_num']."</option>";
    } 
    echo $output;
?>
    
    
    
