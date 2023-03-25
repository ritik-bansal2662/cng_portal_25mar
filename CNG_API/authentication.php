<?php

include "conn.php";

$response = array();
if(isset($_GET['apicall'])) {
    switch($_GET['apicall']) {
        case 'login': 
            if(isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $sql_fetch_data = "select * from luag_employee_registration where Emp_id = '$username' and Emp_Contact_Number = '$password'";
                $fetch_result = mysqli_query($conn, $sql_fetch_data);
                $row = $fetch_result -> fetch_assoc();
                // var_dump($row);
                $count = mysqli_num_rows($fetch_result);
                if($count == 1) {
                    
                    // fetching data from luag_role_mapping table for org details
                    $sql_fetch_role_data = "select * from luag_role_mapping where Employee_id = '$username'";
                    $fetch_role_result = mysqli_query($conn, $sql_fetch_role_data);
                    $count_role_rows = mysqli_num_rows($fetch_role_result);

                    if($count_role_rows == 1) {
                        $role_row = $fetch_role_result -> fetch_assoc();
                        $user_role = $role_row['User_Role'];

                        if($user_role == 'Operator') {
                            $response['error'] = true;
                            $response['message'] = "Unable to login as Operator!";
                        } else {
                            session_start();
                            $_SESSION['loggedin']= true;
                            $_SESSION['user_role']=$user_role;

                            if($user_role == 'Admin') {
                                $response['error'] = false;
                                $response['message'] = "Logged in as Admin";
                                header("location: ../html/index.php");
                            } else if($user_role == 'Manager') {
                                $response['error'] = false;
                                $response['message'] = "Logged in as Manager";
                                header("location: ../html/index.php");
                            }
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Unable to fetch data";
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Invalid Credentials or User does not exists.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "All fields are mandatory.";
            }
        break;
    }
}

// echo json_encode($response);
?>