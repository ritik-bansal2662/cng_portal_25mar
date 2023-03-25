<?php 
include '../../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

$response = array();

if(isset($_POST['mobile'])){

    
    $mobile = $_POST['mobile'];

    $select_sql = "select * from luag_employee_registration where Emp_Contact_Number = '$mobile'";
    $result = mysqli_query($conn, $select_sql);
    $num_rows = mysqli_num_rows($result);

    if($num_rows>0){

        $emp_id='';
        while($row = $result-> fetch_assoc()) {
            $emp_num = $row['Emp_num'];
            $emp_id = $row['Emp_id'];
        }
        // $detail_sql = "select * from luag_role_mapping where Employee_id = '$emp_id'";
        $stmt= $conn->prepare("select Employee_Id,
            User_Role,
            Orgnization_Id,
            note_approver_mgs,
            note_approver_dbs
            from luag_role_mapping where Employee_id = ?"
        );
        $stmt->bind_param("s", $emp_num);
        $detail_result= $stmt->execute();
        // $detail_num_rows = mysqli_num_rows($detail_result);
        if($detail_result==TRUE){ 
            $stmt->store_result();
            $stmt->bind_result(
                $Employee_num,
                $User_Role,
                $Orgnization_Id,
                $note_approver_mgs,
                $note_approver_dbs
            );
            $stmt->fetch();
            
            if($Employee_num==null) {
                $response['error'] = true;
                $response['message'] = "User has not been assigned a role, please assign first.";
            } else {
                $response['error'] = false;
                $response['message'] = "Retrieval Successful!";
                $response['employee_num']=$Employee_num;
                $response['emp_id'] = $emp_id;
                $response['User_Role']=$User_Role;
                $response['Orgnization_Id']=$Orgnization_Id;
                $response['note_approver_mgs']=$note_approver_mgs;
                $response['note_approver_dbs']=$note_approver_dbs;
            }
        } else {
            $response['error'] = true;
            $response['message'] = "User has not been assigned a role, please assign first.";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "No User with this mobile number!";
    }

} else {
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}
    
echo json_encode($response);

?>

