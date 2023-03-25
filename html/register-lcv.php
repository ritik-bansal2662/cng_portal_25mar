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

?>


<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<style>
    .reg-main div {
        cursor: pointer;
        width: 100%;
        height: 100%;
        padding-top: 10px;
        text-align: center;
    }

    .main-active {
        border-bottom: 5px solid brown;
    }

    .gen-info.content-active,
    .cas-info.content-active {
        display: block;
    }

    h3 {
        text-align: center;
    }


    .input {
        /* width: 200px; */
        height: 40px;
        font-size: 18px;
        padding-left: 20px;
        margin: 10px;
        /* display: block; */
        border-radius: 8px;
    }

    .buttons {
        display: flex;
        justify-content: start;
        align-items: center;
    }

    .cancel-btn,
    .submit-btn {
        width: 100px;
        margin: 10px;
    }

    #lcv_mgs {
        font-size: 18px;
        /* padding-left: 20px; */
        /* margin-right: auto;
        margin-left: auto; */
        margin:0;
        border-radius: 8px;
    }
</style>

<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white;">LCV Registration</h3>
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
                <div class='gen-info content-active'>
                    <!-- method='post' action='../CNG_API/reg_lcv.php?apicall=insertLcvGenInfo' -->
                    <form id='lcv_general_form' enctype="multipart/form-data" class='reg-form'>
                        <!-- <input type='text' name='Lcv_Registered_To' placeholder='Registered Partner/Main organization Name' required class='input col-md-6 col-12' /> -->
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <label for='Lcv_Registered_To' class='label col-12'>LCV Vendor</label>
                                <!-- <select required name='Lcv_Registered_To' id='Lcv_Registered_To' class='input col-12'>
                                </select> -->
                                <input required name='Lcv_Registered_To' placeholder='Lcv Registered To' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <label for='Lcv_Num' class='label col-12'>LCV Number</label>
                                <input required type='text' name='Lcv_Num' placeholder='LCV Number' class='input col-12' required />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <label for='Vechicle_Type' class='label col-12'>Vechicle Type</label>
                                <input required type='text' name='Vechicle_Type' placeholder='Vehicle Type' class='input col-12' required />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <label for='Chassis_Num' class='label col-12'>Chassis Number</label>
                                <input required type='text' name='Chassis_Num' placeholder='Chassis Number' class='input col-12' required />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <label for='Engine_Num' class='label col-12'>Engine Number</label>
                                <input required type='text' name='Engine_Num' placeholder='Engine Number' class='input col-12' required />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <label for='Cascade_Capacity' class='label col-12'>Capacity of Cascade</label>
                                <input required type='number' name='Cascade_Capacity' placeholder='Capacity of Cascade' class='input col-12' required />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <label for='Lcv_Maker' class='label col-12'>LCV Maker's Name</label>
                                <input required type='text' name='Lcv_Maker' placeholder="LCV Maker's Name" class='input col-12' required />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <label for='Fuel_Type' class='label col-12'>Fuel Used</label>
                                <input required type='text' name='Fuel_Type' placeholder='Fuel Used' class='input col-12' required />
                            </div>
                        </div>
                        

                        <div class='form-buttons col-12'>
                            <input type='button' class='btn btn-warning cancel-btn' value='Reset' />
                            <button type='button' id='lcv_general_submit' class='btn btn-primary submit-btn'>Submit</button>
                        </div>
                    </form>
                </div>

                <div class='container cas-info'>
                    <!-- method='post' action='../CNG_API/lcv_instrument_info.php?apicall=insertLcvCascadeInfo' -->
                    <form id='lcv_cascade_form' enctype="multipart/form-data" class='reg-form'>
                        <!-- <input required type='text' name='lcv_registered_to' placeholder='Registered Partner/Main organization Name' class='input col-md-6 col-12' /> -->
                        <div class='col-12 inp-group'>
                            <!-- <div class='col-lg-5 col-12'>
                                <label for='lcv_num' class='label col-12'>Select LCV Number</label>
                                <select required name='lcv_num' id='lcv_num' class='input col-12'>
                                </select>
                            </div> -->
                            <div class='col-lg-5 col-12'>
                                <label for='lcv_num' class='label col-12'>Select LCV Number</label>
                                <select name='lcv_num' class='input col-12' id='lcv_num' >
                                    <!-- LCV Numbers are fetched from database according to organization using ajax -->
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
                                            $lcv_output = "<option value='NA'>No LCV found for selected organization</option>";
                                        }
                                    
                                        echo $lcv_output;
                                    
                                    ?>
                                </select>
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='cas_lcv_registered_to' class='label col-12'>LCV Vendor</label>
                                <input required name='lcv_registered_to' id='cas_lcv_registered_to' placeholder='LCV Vendor' class='input col-12' />
                            </div>
                            <!-- <div class='col-lg-5 col-12'></div> -->
                        </div>
                        <hr>
                        <h2>Tempraure Gauge</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_gauge_id' class='label col-12'>Tempraure Gauge Id</label>
                                <input required name='temperature_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_gauge_make' class='label col-12'>Tempraure Gauge Make</label>
                                <input required type='text' name='temperature_gauge_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_model' class='label col-12'>Tempraure Gauge Model</label>
                                <input required type='text' name='temperature_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_last_calibration_date' class='label col-12'>Calibration Date</label>
                                <input required type="date" id="installation-date" name="temperature_last_calibration_date" class='input col-12'>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='temperature_claibration_cycle' class='label col-12'>Calibration Cycle</label>
                                <input required type='text' name='temperature_claibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'></div>
                        </div>
                        <hr>                
                        </br>
                        <h2>Pressure Gauge</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_id' class='label col-12'>Pressure Gauge Id</label>
                                <input required name='pressure_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_make' class='label col-12'>Pressure Gauge Make</label>
                                <input required type='text' name='pressure_gauge_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_model' class='label col-12'>Pressure Gauge Model</label>
                                <input required type='text' name='pressure_gauge_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure-calibration-date' class='label col-12'>Calibration Date</label>
                                <input required type="date" id="installation-date" name="pressure_gauge_claibration_date" class='input col-12'>
                            </div>
                        </div>
                        
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='pressure_gauge_calibration_cycle' class='label col-12'>Calibration Cycle</label>
                                <input required type='text' name='pressure_gauge_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'></div>
                        </div>
                        <hr>
                        <h2>Stationary Cascade</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_id' class='label col-12'>Stationary Cascade Id</label>
                                <input required name='stationary_cascade_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_make' class='label col-12'>Stationary Cascade Make</label>
                                <input required type='text' name='stationary_cascade_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_model' class='label col-12'>Stationary Cascade Model</label>
                                <input required type='text' name='stationary_cascade_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_serial_number' class='label col-12'>Stationary Cascade Serial Number</label>
                                <input required type='text' name='stationary_cascade_serial_number' placeholder='Serial Number' class='input col-12' />
                            </div>
                        </div>
                        
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_installation_date' class='label col-12'>Date of Installation</label>
                                <input required type="date" id="install-date" name="stationary_cascade_installation_date" class='input col-12'>
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_hydrotest_status_date' class='label col-12'>Hydro Status Date</label>
                                <input required type="date" id="stationary_cascade_hydrotest_status_date" name="stationary_cascade_hydrotest_status_date" class='input col-12'>
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_hydrotest_status' class='label col-12'>Hydro Status</label>
                                <input required type='text' name='stationary_hydrotest_status' placeholder='Hydrotest Status' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12'>
                                <label for='stationary_cascade_capacity' class='label col-12'>Capacity of Stationary Cascade</label>
                                <input required type='text' name='stationary_cascade_capacity' placeholder='Capacity of Stationary Cascade' class='input col-12' />
                            </div>
                        </div>
                        <hr>

                        <div class='form-buttons col-12'>
                            <input class='btn btn-warning cancel-btn' value='Reset' />
                            <button type='button' id='lcv_cascade_submit' class='btn btn-primary submit-btn'>Submit</button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
    <Script type="text/javascript">
        <?php include '../dist/js/register-lcv.js' ?> 
    </Script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script> -->

    <?php include('footer.php'); ?>


</body>

</html>