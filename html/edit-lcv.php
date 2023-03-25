<?php

// include './CNG_API/read_lcv_gen_info.php';
// include './CNG_API/read_lcv_instrument_info.php';

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php'); ?>

</head>
<body>

    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white; ">Edit/View LCV</h3>
            </div>
            <div class='reg-main'>
                <div class='gen main-active'>General Information</div>
                <div class='cas'>Cascade Information</div>
            </div>
        </div>

        <!-- for extra space -->
        <div class="top-temp-extra"></div> 
        
        <div class="container-fluid" style="background-color: #fff999;">
            <div>
                <div class='container gen-info content-active' >
                    <!-- method='post' action='../../CNG_API/reg_lcv.php?apicall=updateLcvGenInfo' -->
                    <form id='lcv_gen_edit_form' enctype="multipart/form-data" class='reg-form'>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='Lcv_Num' class='label col-12'>Select LCV Number</label>
                                <select name='Lcv_Num' class='input col-12' id='Lcv_Num' >
                                    <?php 
                                        $lcv_select_sql = "SELECT Lcv_Num from reg_lcv";
                                        $lcv_result = mysqli_query($conn, $lcv_select_sql );
                                        $lcv_num_rows = mysqli_num_rows($lcv_result );
                                        $lcv_output='';
                                        if($lcv_num_rows > 0) {
                                            $lcv_output.= "<option value='NA'>Select Lcv Number</option>";
                                            while($lcv_row = $lcv_result-> fetch_assoc()) {
                                                $lcv_output .= "<option value='".$lcv_row['Lcv_Num']."'>".$lcv_row['Lcv_Num']."</option>";
                                            }
                                        } else {
                                            $lcv_output = "<option value='NA'>No LCV found</option>";
                                        }
                                    
                                        echo $lcv_output;
                                    
                                    ?>
                                </select>
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='Lcv_Registered_To' class='label col-12'>LCV Vendor</label>
                                <input readonly name='Lcv_Registered_To' class='input col-12' id='Lcv_Registered_To' placeholder='LCV Vendor'>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='Vechicle_Type' class='label col-12'>Vechicle Type</label>
                                <input type='text' name='Vechicle_Type' id='Vechicle_Type' placeholder='Vehicle Type' class='input col-12' required />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='Chassis_Num' class='label col-12'>Chassis Number</label>
                                <input type='text' name='Chassis_Num' id='Chassis_Num' placeholder='Chassis Number' class='input col-12' required />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='Engine_Num' class='label col-12'>Engine Number</label>
                                <input type='text' name='Engine_Num' id='Engine_Num' placeholder='Engine Number' class='input col-12' required />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='Cascade_Capacity' class='label col-12'>Capacity of Cascade</label>
                                <input type='number' name='Cascade_Capacity' id='Cascade_Capacity' placeholder='Capacity of Cascade' class='input col-12' required />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='Lcv_Maker' class='label col-12'>LCV Maker's Name</label>
                                <input type='text' name='Lcv_Maker' id='Lcv_Maker' placeholder="LCV Maker's Name" class='input col-12' required />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='Fuel_Type' class='label col-12'>Fuel Used</label>
                                <input type='text' name='Fuel_Type' id='Fuel_Type' placeholder='Fuel Used' class='input col-12' required />
                            </div>
                        </div>

                        <div class='form-buttons col-12'>
                            <button class='btn btn-warning cancel-btn'>cancel</button>
                            <button type='button' id='lcv_gen_edit_submit' class='btn btn-primary submit-btn'>Update</button>
                        </div>
                    </form>
                </div>

                <div class='container cas-info' style="background-color: #fff999;">
                    <!-- method='post' action='../../CNG_API/lcv_instrument_info.php' -->
                    <form id='lcv_instrument_edit_form' enctype="multipart/form-data" class='reg-form'>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='lcv_Num' class='label col-12'>Select LCV Number</label>
                                <select name='lcv_num' class='input col-12' id='lcv_num' >
                                    <?php 
                                        $lcv_cas_select_sql = "SELECT lcv_num FROM reg_instrument_lcv";
                                        $lcv_cas_result = mysqli_query($conn, $lcv_cas_select_sql );
                                        $lcv_cas_num_rows = mysqli_num_rows($lcv_cas_result );
                                        $lcv_cas_output='';
                                        if($lcv_cas_num_rows > 0) {
                                            $lcv_cas_output.= "<option value='NA'>Select Lcv Number</option>";
                                            while($lcv_cas_row = $lcv_cas_result-> fetch_assoc()) {
                                                $lcv_cas_output .= "<option value='".$lcv_cas_row['lcv_num']."'>".$lcv_cas_row['lcv_num']."</option>";
                                            }
                                        } else {
                                            $lcv_cas_output = "<option value='NA'>No LCV found</option>";
                                        }
                                    
                                        echo $lcv_cas_output;
                                    
                                    ?>
                                </select>
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='cas_lcv_registered_to' class='label col-12'>LCV Vendor</label>
                                <input readonly name='lcv_registered_to' placeholder='LCV Vendor' class='input col-12' id='lcv_registered_to'>
                            </div>
                            <!-- <div class='col-lg-5 col-12'>
                                <label for='lcv_num' class='label col-12'>Select LCV Number</label>
                                <select name='lcv_num' class='input col-12' id='lcv_num'>
                                </select>
                            </div> -->
                        </div>

                        <hr>

                        <h2>Temperature Gauge</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_gauge_id' class='label col-12'>Tempraure Gauge Id</label>
                                <input required name='temperature_gauge_id' id='temperature_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_gauge_make' class='label col-12'>Tempraure Gauge Make</label>
                                <input required type='text' name='temperature_gauge_make' id='temperature_gauge_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_model' class='label col-12'>Tempraure Gauge Model</label>
                                <input required type='text' name='temperature_model' id='temperature_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_last_calibration_date' class='label col-12'>Calibration Date</label>
                                <input required type="date" id="temperature_last_calibration_date" name="temperature_last_calibration_date" class='input col-12'>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_claibration_cycle' class='label col-12'>Calibration Cycle</label>
                                <input required type='text' name='temperature_claibration_cycle' id='temperature_claibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                            </div>
                        </div>

                        <hr>
                        
                        <h2>Pressure Gauge</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_id' class='label col-12'>Pressure Gauge Id</label>
                                <input required name='pressure_gauge_id' id='pressure_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_make' class='label col-12'>Pressure Gauge Make</label>
                                <input required type='text' name='pressure_gauge_make' id='pressure_gauge_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_model' class='label col-12'>Pressure Gauge Model</label>
                                <input required type='text' name='pressure_gauge_model' id='pressure_gauge_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure-calibration-date' class='label col-12'>Calibration Date</label>
                                <input required type="date" id="pressure_gauge_claibration_date" name="pressure_gauge_claibration_date" class='input col-12'>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_calibration_cycle' class='label col-12'>Calibration Cycle</label>
                                <input required type='text' name='pressure_gauge_calibration_cycle' id='pressure_gauge_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                            </div>
                        </div>

                        <hr>
                        
                        <h2>Stationary Cascade</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_id' class='label col-12'>Stationary Cascade Id</label>
                                <input required name='stationary_cascade_id' id='stationary_cascade_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_make' class='label col-12'>Stationary Cascade Make</label>
                                <input required type='text' name='stationary_cascade_make' id='stationary_cascade_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_model' class='label col-12'>Stationary Cascade Model</label>
                                <input required type='text' name='stationary_cascade_model' id='stationary_cascade_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_serial_number' class='label col-12'>Stationary Cascade Serial Number</label>
                                <input required type='text' name='stationary_cascade_serial_number' id='stationary_cascade_serial_number' placeholder='Serial Number' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_installation_date' class='label col-12'>Date of Installation</label>
                                <input required type="date" id="stationary_cascade_installation_date" name="stationary_cascade_installation_date" class='input col-12'>
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_hydrotest_status_date' class='label col-12'>Hydro Status Date</label>
                                <input required type="date" id="stationary_cascade_hydrotest_status_date" name="stationary_cascade_hydrotest_status_date" class='input col-12'>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_hydrotest_status' class='label col-12'>Hydro Status</label>
                                <input required type='text' name='stationary_hydrotest_status' id='stationary_hydrotest_status' placeholder='Hydrotest Status' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_capacity' class='label col-12'>Capacity of Stationary Cascade</label>
                                <input required type='text' name='stationary_cascade_capacity' id='stationary_cascade_capacity' placeholder='Capacity of Stationary Cascade' class='input col-12' />
                            </div>
                        </div>
                        
                        <div class='form-buttons col-12'>
                            <button class='btn btn-warning cancel-btn'>cancel</button>
                            <button type='button' id='lcv_instrument_edit_submit' class='btn btn-primary submit-btn'>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script>
        <?php include '../dist/js/edit-lcv.js' ?>
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <script>

        //General
        // function genLcvId(id) {
        //     stId=id
        //     var req= new XMLHttpRequest()
        //     req.open("GET", "http://localhost/CNGPORTAL/CNGPORTAL/CNG_API/read_lcv_gen_info.php?id="+stId, true);

        //     req.onreadystatechange=function() {
        //         if(req.readyState==4 && req.status==200) {
        //             var res = req.responseText;
        //             myObj = JSON.parse(res);
        //             for(x in myObj) {
        //                 document.getElementById(myObj[x].name).value=myObj[x];
        //             }
        //         }
        //     }
        // }

        //Instrument
        // function insLcvId(Id) {
        //     stId=Id
        //     var req= new XMLHttpRequest()
        //     req.open("GET", "http://localhost/apc/gas-dashboard/CNG_API/read_lcv_instrument_info.php?id="+stId+"&type="+stId, true);

        //     req.onreadystatechange=function() {
        //         if(req.readyState==4 && req.status==200) {
        //             var res = req.responseText;
        //             myObj = JSON.parse(res);
        //             for(x in myObj) {
        //                 document.getElementById(myObj[x].name).value=myObj[x];
        //             }
        //         }
        //     }
        // }
            

    </script>

    <?php include('footer.php'); ?>

</body>
</html>

