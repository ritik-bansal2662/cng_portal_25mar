<?php 
include '../../CNG_API/conn.php';
// $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

if(isset($_POST['org'])){
    $org = $_POST['org'];

    $response = array(array());

    $select_sql = "select * from luag_employee_registration where Emp_Orgnization_id = '$org'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);
    $output = "<option value='NA'>Select Employee</option>";
    $i=0;
    while($row = $result-> fetch_assoc()) {
        $response[$i][0]=$row['Emp_id'];
        $response[$i][1]=$row['Emp_First_Name'] . " " . $row['Emp_Middle_Name'] . " " . $row['Emp_Last_Name'];
        $output .= "<option value='".$row['Emp_Contact_Number']."'>".$row['Emp_id'] . " - " . $row['Emp_First_Name'] . " " . $row['Emp_Middle_Name'] . " " . $row['Emp_Last_Name'] . "</option>";
        $i++;
    }

    echo $output;
}
?>