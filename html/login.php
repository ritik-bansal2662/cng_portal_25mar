<?php


$response = array();

function unset_session() {
    unset($_SESSION['loggedin']);
    unset($_SESSION['user_role']);
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);
    unset($_SESSION['mgs_id']);
    unset($_SESSION['dbs_id']);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "../CNG_API/conn.php";
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
                    // $emp_num= strtolower(substr($row['Emp_num'],0,3));
                    $emp_num = $row['Emp_num'];
                    $sql_fetch_role_data = "select * from luag_role_mapping where Employee_id = '$emp_num'";
                    $fetch_role_result = mysqli_query($conn, $sql_fetch_role_data);
                    $count_role_rows = mysqli_num_rows($fetch_role_result);
                    
                    if($count_role_rows == 1) {
                        $role_row = $fetch_role_result -> fetch_assoc();
                        // var_dump($role_row);
                        $user_role = $role_row['User_Role'];

                        if($user_role == 'Operator') {
                            $response['error'] = true;
                            $response['message'] = "User not authorized to access the portal.";
                            // print_r($response);
                            // echo $response['message'];
                        } else {
                            session_start();
                            $_SESSION['loggedin']= true;
                            $_SESSION['user_role']=$user_role;
                            $_SESSION['name'] = $row['Emp_First_Name'];
                            $_SESSION['user_id'] = $row['Emp_id'];
                            $_SESSION['mgs_id'] = $role_row['note_approver_mgs'];
                            $_SESSION['dbs_id'] = $role_row['note_approver_dbs'];
                            $emp_id = $row['Emp_id'];

                            $status_sql = "UPDATE luag_employee_registration 
                                set status='Active' 
                                WHERE Emp_id='$emp_id'";
                            $status_result = mysqli_query($conn, $status_sql);

                            if($status_result){
                                if($user_role == 'Admin') {
                                    $response['error'] = false;
                                    $response['message'] = "Logged in as Admin";

                                    $_SESSION['admin'] = true;
                                    $_SESSION['manager'] = false;
                                    header("location: index.php");
                                    exit();
                                } else if($user_role == 'Manager') {
                                    $_SESSION['man'] = true;
                                    $response['error'] = false;
                                    $response['message'] = "Logged in as Manager";
                                    
                                    $_SESSION['manager'] = true;
                                    $_SESSION['admin'] = false;
                                    header("location: index.php");
                                    exit();
                                } else {
                                    $response['error'] = true;
                                    $response['message'] = "Not Authorized to access the portal";
                                    unset_session();
                                    $status_sql = "UPDATE luag_employee_registration 
                                        set status='Inactive' 
                                        WHERE Emp_id='$emp_id'";
                                    $status_result = mysqli_query($conn, $status_sql);
                                }
                            } else {
                                $response['error'] = true;
                                $response['message'] = "Unable to login at this moment.";
                                unset_session();
                            }
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "User is not Assigned any role, assign role first.";
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Invalid Credentials or User does not exists.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "All fields are mandatory.";
            }
}

// echo json_encode($response);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php //include('head.php'); ?>
    <style>
        <?php include '../dist/css/login.css' ?>
    </style>
</head>
<body>
    <?php //include('header.php'); ?>
    <div class="page-wrapper main-content">
        <div class="page-breadcrumb">
            <div class="align-self-center logo">
                <img src='../assets/images/apc.png' alt='APC LOGO' />
                <!-- <img src='../../assets/images/LUAG_images/background_login.png' alt='mgs' /> -->
            </div>
        </div>
            <div class="authenticate">
                <div class='login_error'>
                    <?php 
                        if(isset($response['error'])) {
                            if($response['error'] == true) {
                                echo $response['message'];
                            }
                        }
                    ?>
                </div>
                <!-- <div class='login-main' >
                    <div class='login'>
                            <img src='../assets/images/assetplus.jpeg' class='login-img-logo' alt='APC logo' />
                            <form action='login.php' method='post' enctype='multipart/form-data' id='login_form' class='login-form'>
                                <input required type="text" class='input' name='username' placeholder='Enter Employee ID' >
                                <input required type='text' class='input' name='password' placeholder='Password' >
                                <button type='submit'>Login</button>
                            </form>
                    </div>
                </div> -->
                <h1>Login</h1>
                <!-- <img src='../assets/apc.png' class='login-img-logo' alt='APC logo' /> -->
                <form action='login.php' method='post'>
                    <div class="txt_field">
                        <input required type="text" name='username' >
                        <span></span>
                        <label>Username</label>
                    </div>
                    <div class="txt_field">
                        <input required type='password' name='password' >
                        <span></span>
                        <label>Password</label>
                    </div>
                    <input type='submit' value='Login' />
                </form>
            </div>
        </div>
    </div>
    <?php // include('footer.php'); ?>
</body>
</html>