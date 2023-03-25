<?php

session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}
if(!(isset($_SESSION['admin']) && $_SESSION['admin'] == true && $_SESSION['manager'] == false)) {
    header('location: index.php');
    exit();
}

include '../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<body>


    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <div class="page-breadcrumb">
            <div class="text-white" style="text-align: center;">
                <h3 style="color:white;">Organization and Employee Registration</h3>
            </div>
            
            <div class='reg-main'>
                <div class='org main-active'>Organization</div>
                <div class='emp'>Employee</div>
            </div>
        </div>
        <!-- for extra space -->
        <div class="top-temp-extra"></div> 
        <div class="container-fluid" style="background-color: #fff999;">
            <div class=''>
                <div class='organization content-active'>
                    <!-- method='post' action='../CNG_API/admin_tab.php?apicall=org_reg' -->
                    <form id='org_reg_form' enctype="multipart/form-data" class='reg-form'>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12 m-auto">
                                <input type="radio" id="partner-org" name="Org_type" value="Partner">
                                <label for='partner-org'>Register as Partner Organization</label><br>
                            </div>
                            <div class="col-lg-5 col-12 m-auto"> 
                                <input type="radio" id="main-org" name="Org_type" value="Main">
                                <label for='main-org'>Register as Main Organization</label><br>
                            </div>
                        </div>

                        <hr />
                        
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_id' class='col-12'>Organization ID</label>
                                <input name='Org_id' id='Org_id' class='input col-12' placeholder='Organization Id'/>
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Full_Name' class='col-12'>Organization Full Name</label>
                                <input name='Org_Full_Name' class='input col-12' placeholder='Organization Name'/>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Short_Name' class='col-12'>Abbrevation of Organization</label>
                                <input type='text' name='Org_Short_Name' placeholder='Abbrevation of Organization' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Sector' class='col-12'>Sector of Organization</label>
                                <input type='text' name='Org_Sector' placeholder='Sector of Organization' class='input col-12' />    
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Address-l-1' class='col-12'>Address line 1</label>
                                <input type='text' name='Address-l-1' placeholder='Address line 1' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Address-l-2' class='col-12'>Address line 2</label>
                                <input type='text' name='Address-l-2' placeholder='Address line 2' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Address-l-3' class='col-12'>Address line 3</label>
                                <input type='text' name='Address-l-3' placeholder='Address line 3' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='city' class='col-12'>City</label>
                                <input type='text' name='city' placeholder='City' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='state' class='col-12'>State</label>
                                <input type='text' name='state' placeholder='State' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='postal-code' class='col-12'>Postal Code</label>
                                <input type='number' name='postal-code' placeholder='Postal Code' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Contact_Person class='col-12''>Name of Contact Person</label>
                                <input type='text' name='Org_Contact_Person' placeholder='Name of Contact Person' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Mobile_Number' class='col-12'>Mobile Number of Organization</label>
                                <input type='number' name='Org_Mobile_Number' placeholder='Mobile Number of Organization' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Landline_Number' class='col-12'>Landline Number of Organization</label>
                                <input type='number' name='Org_Landline_Number' placeholder='Landline Number of Organization' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <div class='location-gps col-12 color-brown' onclick="getLocation()">Click here to locate Station through GPS</div>
                                <input class='input col-12' id='Org_Location'  placeholder='Latitide and Longitude' name='Org_Location' />
                            </div>
                        </div>
                        </br>
                        <div class='form-buttons col-12 inp-group'>
                            <button type="button" id='org-reset' class='btn btn-warning cancel-btn  btn-lg active'>Reset</button>
                            <button type="button" id='org_reg_submit' class='btn btn-primary submit-btn  btn-lg active'>Proceed</button>
                        </div>
                    </form> 
                </div>

                <div class='employee'>
                    <!-- <h3>Register as Employee</h3> -->
                    <!-- action='../CNG_API/admin_tab.php?apicall=emp_reg'  -->
                    <form id='emp_reg_form' class='reg-form'>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12 m-auto">
                                <input type="radio" id="partner-emp" name="Emp_Type" value="Partner">
                                <label for='partner-emp'>Register as Partner Employee</label><br>
                            </div>
                            <div class="col-lg-5 col-12 m-auto">
                                <input type="radio" id="main-emp" name="Emp_Type" value="Main">
                                <label for='partner-emp'>Register as Employee</label><br>
                            </div>
                        </div>

                        <hr />

                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Orgnization_id' class='col-12'>Select Organization Id</label>
                                <select name='Emp_Orgnization_id' id='Emp_Orgnization_id' class='input col-12'>
                                </select>
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_id' class='col-12'>Employee/User Id</label>
                                <input type='text' name='Emp_id' placeholder='Employee/User Id' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_First_Name' class='col-12'>First Name</label>
                                <input type='text' name='Emp_First_Name' placeholder='First Name' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Middle_Name' class='col-12'>Middle Name</label>
                                <input type='text' name='Emp_Middle_Name' placeholder='Middle Name' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Last_Name' class='col-12'>Last Name</label>
                                <input type='text' name='Emp_Last_Name' placeholder='Last Name' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Age' class='col-12'>Age</label>
                                <input type='number' name='Emp_Age' placeholder='Age' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Contact_Number' class='col-12'>Contact Number</label>
                                <input type='number' name='Emp_Contact_Number' placeholder='Contact Number' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Email_Id' class='col-12'>Email</label>
                                <input type='email' name='Emp_Email_Id' placeholder='Email' pattern="[^ @]*@[^ @]*" class='input col-12' />
                            </div>
                        </div>
                        <div class='form-buttons col-12'>
                            <button type="button" class='btn btn-warning cancel-btn  btn-lg active'>Reset</button>
                            <button type="button" id='emp_reg_submit' class='btn btn-primary submit-btn  btn-lg active'>Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <Script type="text/javascript">
        <?php include '../dist/js/registration.js'; ?>
    </Script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <?php include('footer.php'); ?>
    


</body>

</html>