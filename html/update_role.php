<?php

session_start();
$conn = mysqli_connect('localhost', 'root', '', 'cng_luag');

$response = array();

if (isset($_POST['emp_id']) && isset($_POST['edit_user_role'])) {
    $role = $_POST['edit_user_role'];
    $org = $_POST['organization'];
    $id = $_POST['emp_id'];
    if(isset($_POST["note_approver_dbs"])){
        $notifApproveDbs = $_POST["note_approver_dbs"];
    } else {
        $notifApproveDbs = "NA";
    }
    if(isset($_POST["note_approver_mgs"])){
        $notifApproveMgs = $_POST["note_approver_mgs"];
    } else {
        $notifApproveMgs='NA';
    }
    $emp_num=strtolower(substr($role, 0, 3)) . $id;
    // echo $role. " ". $org . " ". " ". $id. " ". $notifApproveDbs." " . $notifApproveDbs;
    // echo ($role . $org . $id . $notifApproveMgs . $notifApproveDbs);

    // $sql_insert = "INSERT INTO `luag_role_mapping`( `Employee_Id`, `User_Role`, `Orgnization_Id`, `note_approver_mgs`, `note_approver_dbs` )
    //  VALUES
    //   ('$id','$role','$org','$notifApproveMgs','$notifApproveDbs')";
    if($id=='' || $org=='') {
      $response['error']=true;
      $response['message']='Error! Employee Id and Organization cannot be NULL.';
    } elseif($role =='NA') {
        $response['error']=true;
        $response['message']='Must Select a role for user.';
    }
    else{
      if($role =='Manager' && $notifApproveMgs == 'NA' && $notifApproveDbs == 'NA'){
        $response['error']=true;
        $response['message']='Must Select a notification approver for manager.';
      }
      else{
        if($role == 'Admin' || $role == 'Operator') {
          $notifApproveMgs == 'NA';
          $notifApproveDbs == 'NA';
        }

        $temp = "SELECT Id from luag_role_mapping where Employee_Id = (SELECT Emp_num from luag_employee_registration where Emp_id = '$id')";
        $temp_result = mysqli_query($conn, $temp);
        $temp_row = $temp_result-> fetch_assoc();

        $role_id = $temp_row['Id'];

        $role_update_sql = "update luag_role_mapping set 
            User_Role ='$role', 
            note_approver_mgs='$notifApproveMgs', 
            note_approver_dbs='$notifApproveDbs',
            Employee_Id ='$emp_num'
            where Id = '$role_id'";

        $sql_update = "update luag_employee_registration set Emp_num = '$emp_num' where Emp_id = '$id'";

        $role_update_result = mysqli_query($conn, $role_update_sql);
        $update_result = mysqli_query($conn, $sql_update);

        if($role_update_result && $update_result) {
          $response['error'] = false;
          $response['message'] = "Data updated Successfully";
        }
        else {
          $response['error'] = false;
          $response['message'] = "Data not Updated\n".$insert_result;
        }
      }
    }
    echo json_encode($response);
}
