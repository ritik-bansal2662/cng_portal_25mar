<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

$org = $_POST['org'];

$response = array(array());

$select_sql = "SELECT * from luag_employee_registration where Emp_Orgnization_id = '$org' and (Emp_num = 'NA' or Emp_num is NULL)";
$result = mysqli_query($conn, $select_sql);
$num_rows = mysqli_num_rows($result);
$output = "<option value=''>Select Employee Id</option>";
$i=0;
while($row = $result-> fetch_assoc()) {
    $response[$i][0]=$row['Emp_id'];
    $response[$i][1]=$row['Emp_First_Name'] . " " . $row['Emp_Middle_Name'] . " " . $row['Emp_Last_Name'];
    $output .= "<option value='".$row['Emp_id']."'>".$row['Emp_id'] . " - " . $row['Emp_First_Name'] . " " . $row['Emp_Middle_Name'] . " " . $row['Emp_Last_Name'] . "</option>";
    $i++;
}

echo $output;
// echo json_encode($response);


                            // $sql = "SELECT DISTINCT(`Emp_id`) Emp_id FROM `luag_employee_registration`";
                            // $all_categories = mysqli_query($conn, $sql);
                            // while ($category = mysqli_fetch_array(
                            //     $all_categories,
                            //     MYSQLI_ASSOC
                            // )) :;
                            // 
                            //     <option value="<?php echo $category["Emp_id"];
                            //                     
                            //         <?php echo $category["Emp_id"];
                            //         
                            //     </option>
                            // <?php
                            // endwhile;
                            

?>