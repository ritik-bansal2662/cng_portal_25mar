<?php

session_start();
$conn = mysqli_connect('localhost', 'root', '', 'cng_luag');
if (isset($_POST['id'])) {
    $role = $_POST['role'];
    $org = $_POST['org'];
    $id = $_POST['id'];
    $notifApproveMgs = $_POST['notif-approve-mgs'];
    $notifApproveDbs = $_POST['notif-approve-dbs'];
    $emp_num=strtolower(substr($role, 0, 3));
    // echo ($role . $org . $id . $notifApproveMgs . $notifApproveDbs);

    $sql_insert = "INSERT INTO `luag_role_mapping`( `Employee_Id`, `User_Role`, `Orgnization_Id`, `note_approver_mgs`, `note_approver_dbs` )
     VALUES
      ('$id','$role','$org','$notifApproveMgs','$notifApproveDbs')";

    $sql_update = "update luag_employee_registration set Emp_num = '$emp_num$id' where Emp_id = '$id'";

    $insert_result = mysqli_query($conn, $sql_insert);
    $update_result = mysqli_query($conn, $sql_update);
    $output='';
    if($insert_result && $update_result) {
      $output .= "Data Inserted Successfully";
    }
    else {
      $output .= $insert_result;
    }
    echo $output;
}
