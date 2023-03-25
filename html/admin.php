<!DOCTYPE html>
<html lang="en">
<?php

session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}
if(!(isset($_SESSION['admin']) && $_SESSION['admin'] == true && $_SESSION['manager'] == false)) {
    header("location: index.php");
    exit();
}

// $conn = mysqli_connect('localhost', 'root', '', 'cng_luag');
include '../CNG_API/conn.php';
include('head.php'); ?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"> -->


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="text-white" style="text-align: center;">
                <h3 style="color:white;">Role Mapping</h3>

                    <!-- <h1 class=" align-self-center" style="color:brown;  padding-left: 400px;">Role Mapping</h1> -->
            </div>
        </div>

        <div class="container-fluid " style="background-color: #fff999;">

            <div class=''>
                <div class='container organization assign content-active'>
                    <form id='admin_form' enctype="multipart/form-data">

                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' for='org'>Select Organization</label>
                                <select required class='select' id='org' name="organization">
                                    <option value="NA" selected>Select Organization</option>
                                    <?php
                                        $sql = "SELECT DISTINCT(`Org_Short_Name`) Org_Short_Name FROM `luag_organization_registration`";
                                        $all_categories = mysqli_query($conn, $sql);
                                        while ($category = mysqli_fetch_array(
                                            $all_categories,
                                            MYSQLI_ASSOC
                                            )) :;
                                            ?>
                                            <option value="<?php echo $category['Org_Short_Name'];
                                                            ?>">
                                                <?php echo $category["Org_Short_Name"];
                                                ?>
                                            </option>
                                            <?php
                                        endwhile;
                                    ?>
                                </select>
                            </div>
                            <div class='col-lg-5 m-auto col-12 '>        
                                <label class='label col-12' for='id'>Select User/Employee Id</label>
                                <select required class='select' id='id' name='emp_id'>
                                    <option value="NA" selected>Select Employee</option>                            
                                </select>
                            </div>
                        </div>

                        <div class='col-12 inp-group'>
                            <!-- <div class='col-lg-5 m-auto col-12 '>
                                <label class='label' for='role'>Employee Name</label>
                                <input readonly type='text' name='emp_name' id='emp_name' placeholder='Employee Name' class='input col-12' required />
                            </div> -->
                            <div class='col-lg-5 m-auto col-12 '>
                                
                                <label class='label col-12' for='role'>User Role Authorization</label>
                                <select required class='select' name='user_role' id='user-role'>
                                    <option selected value='NA'>Select User Role</option>
                                    <option>Operator</option>
                                    <option>Manager</option>
                                    <option>Admin</option>
                                    <option>Driver</option>
                                </select>
                            </div>

                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' for='notif_approver_station_type'>Select Notificatin approver type</label>
                                <select required class='select' name='notif_approver_station_type' id='notif_approver_station_type'>
                                    <option value='NA'>Select Notification approver type</option>
                                    <option>MGS</option>
                                    <option>DBS</option>
                                </select>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                        
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' id='dbslabel' for='notif-approve-dbs'>Select Notification Approver DBS</label>
                                <select disabled required class='select' id='dbsid' name='note_approver_dbs'>
                                    <option value="NA" selected>Select DBS</option>
                                    
                                    <?php
                                    $sql = "SELECT DISTINCT(Station_Id) Station_Id  from luag_station_master where `Station_type` = 'Daughter Booster Station'";
                                    $all_categories = mysqli_query($conn, $sql);
                                    while ($category = mysqli_fetch_array(
                                        $all_categories,
                                        MYSQLI_ASSOC
                                        )) :;
                                        ?>
                                        <option value="<?php echo $category["Station_Id"];
                                                        ?>">
                                            <?php echo $category["Station_Id"];
                                            ?>
                                        </option>
                                        <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>

                            <div class='col-lg-5 m-auto col-12 '>
                                
                                <label class='label col-12' id='mgslabel' for='notif-approve-mgs'>Select Notification Approver MGS</label>
                                <select disabled required class='select' id='mgsid' name='note_approver_mgs'>
                                    <option value="NA" selected>Select MGS</option>
                                    
                                    <?php
                                        $sql = "SELECT DISTINCT(Station_Id) Station_Id FROM `luag_station_master` where `Station_type` = 'Mother Gas Station'";
                                        $all_categories = mysqli_query($conn, $sql);
                                        while ($category = mysqli_fetch_array(
                                            $all_categories,
                                            MYSQLI_ASSOC
                                            )) :;
                                            ?>
                                            <option value="<?php echo $category["Station_Id"];
                                                            ?>">
                                                <?php echo $category["Station_Id"];
                                                ?>
                                            </option>
                                            <?php
                                        endwhile;
                                    ?>
                                </select>
                            </div>
                            <!-- <div class='col-lg-5 m-auto col-12 '></div> -->
                        </div>
                        
                        <div class='form-buttons col-12'>
                            <button type="button" id='role-edit' class='btn btn-warning cancel-btn  btn-lg active'>Edit</button>
                            <button type='button' id='admin_submit' class='btn btn-primary submit-btn  btn-lg active'>Proceed</button>
                        </div>
                    </form>
                </div>

                <div class='container organization edit' id='edit-role'>
                    <div id='gotoassign' class='back-btn color-brown'>&#8592; Go To Assign Role Page</div>
                    <!-- <br><br> -->
                    <!-- <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                            <input type='number' name='role-mobile' id='role-mobile' class='input col-12' placeholder='Enter Mobile Number' />
                        </div>
                        <div class='col-lg-5 m-auto col-12'>
                            <input type='button' name='get-details' id='getDetails' class='btn m-2 btn-primary' value='Get Details' />
                        </div>
                    </div> -->

                    <div class='col-12 inp-group'>
                        <div class="col-lg-5 col-12">
                            <select name='Orgnization_Id' id='Orgnization_Id' class='input col-12'>
                            </select>
                        </div>
                        <div class="col-lg-5 col-12">
                            <select name='Employee_Id' id='Employee_id' class='input col-9'>
                            </select>
                            <button type='button' id='getDetails' class='btn btn-primary col-2'>Go</button>
                            <!-- <input type='text' name='Emp_id' placeholder='Employee/User Id' class='input col-12' /> -->
                        </div>
                    </div>

                    <hr>

                    <form id='edit-form' enctype="multipart/form-data">
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' for='emp_id'>Employee Id</label>
                                <input readonly name='emp_id' id='emp_id' class='input col-12' placeholder='Employee Id' />
                            </div>
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' for='organization'>Organization Id</label>
                                <input readonly name='organization' id='orgnization' class='input col-12' placeholder='Organization Id'/>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' for='edit_user_role'>User Role Authorization</label>
                                <select required class='select' name='edit_user_role' id='edit_user_role'>
                                    <option selected value='NA'>Select Role</option>
                                    <option>Operator</option>
                                    <option>Manager</option>
                                    <option>Admin</option>
                                </select>
                            </div>
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' for='notif_approver_edit_station_type'>Select Notificatin approver type</label>
                                <select required class='select' name='notif_approver_edit_station_type' id='notif_approver_edit_station_type'>
                                    <option selected value='NA'>Select Notification approver type</option>
                                    <option>MGS</option>
                                    <option>DBS</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' id='edit-mgslabel' for='note_approver_mgs'>Select Notification Approver MGS</label>
                                <select disabled required class='select' id='edit-mgsid' name='note_approver_mgs'>
                                    <option value="NA" selected>Select MGS</option>
                                    <?php
                                        $sql = "SELECT DISTINCT(Station_Id) Station_Id FROM `luag_station_master` where `Station_type` = 'Mother Gas Station'";
                                        $all_categories = mysqli_query($conn, $sql);
                                        while ($category = mysqli_fetch_array(
                                            $all_categories,
                                            MYSQLI_ASSOC
                                            )) :;
                                            ?>
                                            <option value="<?php echo $category["Station_Id"];
                                                            ?>">
                                                <?php echo $category["Station_Id"];
                                                ?>
                                            </option>
                                            <?php
                                        endwhile;
                                    ?>
                                </select>
                            </div>
                        <!-- </div>
                        <div class='col-12 inp-group'> -->
                            <div class='col-lg-5 m-auto col-12 '>
                                <label class='label col-12' id='edit-dbslabel' for='notif_approve_dbs'>Select Notification Approver DBS</label>
                                <select disabled required class='select' id='edit-dbsid' name='note_approver_dbs'>
                                    <option value="NA" selected>Select DBS</option>
                                    <?php
                                        $sql = "SELECT DISTINCT(Station_Id) Station_Id  from luag_station_master where `Station_type` = 'Daughter Booster Station'";
                                        $all_categories = mysqli_query($conn, $sql);
                                        while ($category = mysqli_fetch_array(
                                            $all_categories,
                                            MYSQLI_ASSOC
                                            )) :;
                                            ?>
                                            <option value="<?php echo $category["Station_Id"];
                                                            ?>">
                                                <?php echo $category["Station_Id"];
                                                ?>
                                            </option>
                                            <?php
                                        endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class='form-buttons col-12'>
                            <button type='button' id='edit_submit' class='btn btn-primary submit-btn  btn-lg active'>Update</button>
                        </div>
                    </form>

                </div>


            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
        <Script type="text/javascript">
            <?php include "../dist/js/admin.js" ?>
        </Script>

        <?php include('footer.php'); ?>


</body>

</html>