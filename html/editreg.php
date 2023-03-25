<?php

error_reporting(0);

session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}
if(!(isset($_SESSION['admin']) && $_SESSION['admin'] == true && $_SESSION['manager'] == false)) {
    header('location: index.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"> -->


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="text-white" style="text-align: center;">
                <h3 style="color:white;">Organization and Employee Edit/View</h3>
            </div>
            <div class='reg-main'>
                <div class='org main-active'>Organization</div>
                <div class='emp'>Employee</div>
            </div>
        </div>

        <!-- for extra space -->
        <div class="top-temp-extra"></div> 
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid" style="background-color: #fff999;">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- basic table -->

            <div>
                <!-- ============================================================== -->
                <!-- EDIT ORGANIZATION -->
                <!-- ============================================================== -->
                <div class='container organization content-active'>
                    <div class='col-12 inp-group'>
                        <div class="col-lg-5 col-12">
                            <!-- <label for='organization_id'>Select Organization</label> -->
                            <select name='organization_id' id='organization_id' class='input col-12'>
                            </select>
                        </div>
                        <div class="col-lg-5 col-12">
                            <button id='orgMobile' type='button' name='search' class='btn btn-primary m-2'>Get Organization Details</button>
                        </div>
                    </div>
                    <hr>

                    <form id='org_edit_form' enctype="multipart/form-data" class='reg-form'>
                        <input type="number" name="slno" id="slno" readonly hidden />
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Type' class='label col-12'>Type of Organization(Partner/Main)</label>
                                <input  type='text' name='Org_Type' id='Org_Type' placeholder='Type of Organization(Partner/Main)' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Org_id' class='col-12'>Organization ID</label>
                                <input readonly name='Org_id' id='Org_id' class='input col-12' placeholder='Organization Id'/>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Full_Name' class='label col-12'>Organization Full Name</label>
                                <input  type='text' name='Org_Full_Name' id='Org_Full_Name' placeholder='Fullname of Organization' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Short_Name' class='label col-12'>Abbrevation of Organization</label>
                                <input  type='text' name='Org_Short_Name' id='Org_Short_Name' placeholder='Abbrevation of Organization' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Sector' class='label col-12'>Sector of Organization</label>
                                <input  type='text' name='Org_Sector' id='Org_Sector' placeholder='Sector of Organization' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Full_Address' class='label col-12'>Full Address</label>
                                <input  type='text' name='Org_Full_Address' id='Org_Full_Address' placeholder='Full Address' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Contact_Person' class='label col-12'>Name of Contact Person</label>
                                <input  type='text' name='Org_Contact_Person' id='Org_Contact_Person' placeholder='Name of Contact Person' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Landline_Number' class='label col-12'>Landline Number of Organization</label>
                                <input  type='number' name='Org_Landline_Number' id='Org_Landline_Number' placeholder='Landline Number of Organization' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Org_Mobile_Number' class='label col-12'>Mobile Number of Organization</label>
                                <input  type='number' name='Org_Mobile_Number' id='Org_Mobile_Number' placeholder='Mobile Number of Organization' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <div class='location-gps col-12 color-brown' onclick="getLocation()">Click here to Locate station through GPS</div>
                                <input readonly value="<?php echo $response['Org_Location']; ?>" class='col-12 input' id='Org_Location' name='Org_Location' placeholder='Location(Read Only)'></input>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'></div>
                        </div>
                        
                        <div class='form-buttons col-12'>
                            <button type="button" class='btn btn-warning cancel-btn  btn-lg active'>Reset</button>
                            <button type="button" id='org_edit_submit' class='btn btn-primary submit-btn  btn-lg active'>Update</button>
                        </div>
                    </form>
                </div>


                <!-- ============================================================== -->
                <!-- EDIT EMPLOYEE -->
                <!-- ============================================================== -->
                <div class='container employee'>
                    <!-- <h3>Register as Employee</h3> -->
                        <!-- <div class='col-12 inp-group'>
                            <input type='number' name='Emp_Contact_Number' id='empMobInp' class='input col-lg-5 col-12' placeholder='Enter Mobile Number' >
                            <span class='col-lg-5  col-12'><button type='button' id='empMobile' class='btn btn-primary m-2'>Get Employee Details</button></span>
                        </div> -->
                    <div class='col-12 inp-group'>
                        <div class="col-lg-5 col-12">
                            <select name='Emp_Org_id' id='Emp_Org_id' class='input col-12'>
                            </select>
                        </div>
                        <div class="col-lg-5 col-12">
                            <select name='Employee_id' id='Employee_id' class='input col-9'>
                            </select>
                            <button type='button' id='empMobile' class='btn btn-primary col-2'>Go</button>
                            <!-- <input type='text' name='Emp_id' placeholder='Employee/User Id' class='input col-12' /> -->
                        </div>
                    </div>
                    <hr>
                    
                    <form id='emp_edit_form' enctype="multipart/form-data" class='reg-form'>
                    
                        <input type='number' class='input col-12' name='id' id="id" placeholder='id' readonly hidden>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Type' class='label col-12'>Type of Employee(Partner/Main)</label>
                                <input  type='text' class='input col-12' name='Emp_Type' id="Emp_Type" placeholder='Type of Employee(Partner/Main)' >
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Orgnization_id' class='label col-12'>Organization Short Name</label>
                                <input readonly name='Emp_Orgnization_id' id='Emp_Orgnization_id' type='text' placeholder='Organization ID' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_id' class='label col-12'>Employee/User Id</label>
                                <input readonly type='text' name='Emp_id' id='Emp_id' placeholder='Employee/User Id' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_First_Name' class='label col-12'>First Name</label>
                                <input  type='text' name='Emp_First_Name' id='Emp_First_Name' placeholder='First Name' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Middle_Name' class='label col-12'>Middle Name</label>
                                <input  type='text' name='Emp_Middle_Name' id='Emp_Middle_Name' placeholder='Middle Name' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Last_Name' class='label col-12'>Last Name</label>
                                <input  type='text' name='Emp_Last_Name' id='Emp_Last_Name' placeholder='Last Name' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Age' class='label col-12'>Age</label>
                                <input  type='number' name='Emp_Age' id='Emp_Age' placeholder='Age' class='input col-12' />
                            </div>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Contact_Number' class='label col-12'>Contact Number</label>
                                <input  type='number' name='Emp_Contact_Number' id='Emp_Contact_Number' placeholder='Contact Number' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group align-items-start'>
                            <div class="col-lg-5 col-12">
                                <label for='Emp_Email_Id' class='label col-12'>Email</label>
                                <input  type='email' name='Emp_Email_Id' id='Emp_Email_Id' placeholder='Email' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'></div>
                        </div>
                        
                        <div class='form-buttons col-12'>
                            <button type="button" class='btn btn-warning cancel-btn  btn-lg active'>Reset</button>
                            <button type="button" id='emp_edit_submit' class='btn btn-primary submit-btn  btn-lg active'>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <Script type="text/javascript">
        <?php 
            // require '../dist/js/registration.js';
            require '../dist/js/editreg.js';
        ?>
    </Script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <?php include('footer.php'); ?>

    <script>

        // function empDetails() {
        //     var num = document.getElementById('empMobInp').value
        //     alert("entered mobile number is: " + num)
        //     var req= new XMLHttpRequest()
        //     req.open("GET", "http://localhost/apc/gas-dashboard/CNG_API/read_emp.php?Emp_Contact_Number="+num, true);

        //     req.onreadystatechange=function() {
        //         if(req.readyState==4 && req.status==200) {
        //             var res = req.response.Text;
        //             for(x in res) {
        //                 document.getElementById(x).value=res[x];
        //             }
        //         }
        //     }
        // }
        
        var x = document.getElementById("Org_Location");
        function getLocation() {
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                x.innerHTML = "Geolocation is not supported by this browser.";
                x.value="-";
            }
        }

        function showPosition(position) {
            x.value = position.coords.latitude + "," + position.coords.longitude;
            // console.log(x.value)
        }
    </script>


</body>

</html>