<?php

session_set_cookie_params(0);
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

<style>
    <?php include('../dist/css/register-station.css'); ?>
</style>

<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h3 style="color:white;">Station Registration</h3>
            </div>
            
            <div class='reg-main'>
                <div class='gen col-3 main-active'>General Information</div>
                <div class='eqp col-3'>Equipment Information</div>
                <div class='ins col-3'>Instruments Information</div>
            </div>
        </div>

        <!-- for extra space -->
        <div class="top-temp-extra"></div> 

        <div class="container-fluid" style="background-color: #fff999;">
            <div class='gen-info content-active'>
                <!-- action='../CNG_API/master_reg_edit.php?apicall=insertGenInfo' method='post'  -->
                <form id='gen-form' enctype="multipart/form-data" class='reg-form'>
                    <!-- <input name='apicall' value='insertGenInfo'  /> -->
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Station_type'>Select Station Type &ast;</label>
                            <select required name='Station_type' class='input col-12' id='gen-station-type'>
                                <option value='0'>Select Station Type</option>
                                <option value='City Gas Station'>City Gas Station</option>
                                <option value='Mother Gas Station'>Mother Gas Station</option>
                                <option value='Daughter Booster Station'>Daughter Booster Station</option>
                            </select>
                        </div>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='mgsId' class='gen-mgs'>Select Mother Gas Station &ast;</label>
                            <select disabled required name='mgsId' class='input col-12' id='gen-mgs' value="NA">
                                <option value="NA">Select MGS Id</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='Station_id' class='label'>Station ID &ast;</label>
                            <input required name='Station_id' placeholder='Station ID' type='text' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='Station_Name' class='label'>Name of Gas Station &ast;</label>
                            <input required type='text' name='Station_Name' placeholder='Name of Gas Station' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5  col-12'>
                            <label for='notification_approver_id' class=''>Select Notification Approver Id &ast;</label>
                            <select required name='notification_approver_id' id='gen_notification_approver_id' class='input col-12'>
                            </select>
                        </div>
                        <div class='col-lg-5  col-12'>
                            <!-- <input type='text' placeholder='' class='input extra col-12' readonly /> -->
                            <div class='location-gps color-brown' onclick="getLocation()">Click here to Locate station through GPS &ast;</div>
                            <input required placeholder='Location' class='col-12 input' id='loc' name='Latitude_Longitude'></input>
                        </div>
                    </div>
                    <!-- <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                        </div>
                        <input type='text' placeholder='Type' class='input extra col-lg-5 col-12' readonly />
                    </div> -->

                    <!-- <hr> -->
                    <!-- <div class='col-12 inp-group'>
                        <label class='text-left'>Address</label>
                        <input type='text' placeholder='' class='extra col-lg-5 col-12' readonly />
                    </div> -->
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Address-l-1' class='label'>Address line 1 &ast;</label>
                            <input required type='text' name='Address-l-1' placeholder='Address line 1' class='input col-12' />
                        </div>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Address-l-2' class='label'>Address line 2</label>
                            <input required type='text' name='Address-l-2' placeholder='Address line 2' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Address-l-3' class='label'>Address line 3</label>
                            <input required type='text' name='Address-l-3' placeholder='Address line 3' class='input col-12' />
                        </div>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='city' class='label'>City &ast;</label>
                            <input required type='text' name='city' placeholder='City' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                        <label for='state' class='label'>State &ast;</label>
                            <input required type='text' name='state' placeholder='State' class='input col-12' />
                        </div>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='postal-code' class='label'>Postal Code &ast;</label>
                            <input required type='number' name='postal-code' placeholder='Postal Code' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Station_In_Charge_Name' class='label'>Name of Gas Station Incharge &ast;</label>
                            <input required type='text' name='Station_In_Charge_Name' placeholder='Name of Gas Station Incharge' class='input col-12' />
                        </div>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Station_In_Charge_Contact_Number' class='label'>Contact Number of Incharge &ast;</label>
                            <input required type='number' name='Station_In_Charge_Contact_Number' placeholder='Contact Number of Incharge' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Number_Filling_Bays' class='label'>Number of Filing Bays &ast;</label>
                            <input required type='number' name='Number_Filling_Bays' placeholder='Number of Filing Bays' class='input col-12' />
                        </div>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Number_Dispenser_Per_Bay' class='label'>Number of Dispenser per Bays &ast;</label>
                            <input required type='number' name='Number_Dispenser_Per_Bay' placeholder='Number of Dispenser per Bays' class='input col-12' />
                        </div>
                    </div>
                    
                    <div class='form-buttons col-12'>
                        <input type='button' value='Reset' class='btn btn-warning cancel-btn' />
                        <button type='button' id='gen_reg_submit' class='btn btn-primary submit-btn'>Submit</button>
                    </div>
                </form>
            </div>

            <div class='eqp-info'>
                <!-- action='../CNG_API/master_reg_edit.php?apicall=insertEquipInfo' -->
                <form method='post' id='eqp-form' enctype="multipart/form-data" class='reg-form'>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 m-auto col-12 '>
                            <label for='Station_type' class='col-12'>Select Station Type &ast;</label><br>
                            <select required name='Station_type' id='eqp-station-type' class='input col-12'>
                                <option value='NA'>Select Station Type</option>
                                <option value='City Gas Station'>City Gas Station</option>
                                <option value='Mother Gas Station'>Mother Gas Station</option>
                                <option value='Daughter Booster Station'>Daughter Booster Station</option>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12 m-auto'>
                            <label for='Station_id'>Select Station ID &ast;</label>
                            <select required name='Station_id' id='eqp_station_id' class='input col-12'>
                            </select>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mgsId' class='eqp-mgs'>Select Mother Gas Station &ast;</label>
                            <select disabled name='mgsId' class='input col-12' id='eqp-mgs'>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'></div>
                    </div>
                    
                    <hr>

                    <h2>Stationary Cascade</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='stationary_cascade_id' class='label'>Stationary Cascade Id &ast;</label>
                            <input required name='stationary_cascade_id' placeholder='ID' type='text' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='stationary_cascade_make' class='label'>Make &ast;</label>
                            <input required type='text' name='stationary_cascade_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='stationary_cascade_model' class='label'>Model &ast;</label>
                            <input required type='text' name='stationary_cascade_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='stationary_cascade_serial_number' class='label'>Serial Number &ast;</label>
                            <input required type='text' name='stationary_cascade_serial_number' placeholder='Serial Number' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label required for='stationary_cascade_installation_date'>Date Of Installation &ast;</label>
                            <input required type="date" format='dd-mm-yyyy' id="installation-date" name="stationary_cascade_installation_date" class='input col-12'>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label required for='stationary_cascade_hydrotest_status_date'>Hydro Status Date &ast;</label>
                            <input required type="date" format='dd-mm-yyyy' id="hydro-status-date" name="stationary_cascade_hydrotest_status_date" class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='stationary_hydrotest_status' class='label'>Hydrotest Status &ast;</label>
                            <input required type='number' name='stationary_hydrotest_status' placeholder='Hydrotest Status' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='stationary_cascade_capacity' class='label'>Capacity of Stationary Cascade &ast;</label>
                            <input required type='number' name='stationary_cascade_capacity' placeholder='Capacity of Stationary Cascade' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_reorder_point' class='label'>Cascade Reorder Point &ast;</label>
                            <input required name='stationary_cascade_reorder_point' placeholder='Cascade Reorder Point' class='input col-12'>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_volume' class='label'>Cascade Volume (L) &ast;</label>
                            <input required type='number' name='stationary_cascade_volume' placeholder='Cascade Volume' class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_cylinder_count' class='label'>Number of Cylinders &ast;</label>
                            <input required type='number' name='stationary_cascade_cylinder_count' placeholder='Number of Cylinders' class='input col-12'>
                        </div>
                        <div class='col-lg-5 col-12'></div>
                    </div>
                    
                    <!-- <hr> -->

                    <!-- <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='compressor_count'>Select number of Compressors</label>
                            <select name='compressor_count' class='input col-12' id='compressor_count'>
                                <option value='0'>0</option>
                                <option value='1'>1</option>
                                <option value='2'>2</option>
                                <option value='3'>3</option>
                                <option value='4'>4</option>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'></div>
                    </div>
                    
                    <hr> -->

                    <hr>

                    <h2>Compressor</h2>
                    
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='compressor_id' class='label'>Compressor ID &ast;</label>
                            <input required type='text' name='compressor_id' placeholder='Compressor ID' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='compressor_make' class='label'>Compressor Make &ast;</label>
                            <input required type='text' name='compressor_make' placeholder='Compressor Make' class='input col-12' />
                        </div>
                    </div>
                    
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='compressor_model' class='label'>Compressor Model &ast;</label>
                            <input required type='text' name='compressor_model' placeholder='Compressor Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='compressor_serial_number' class='label'>Compressor Serial Number &ast;</label>
                            <input required type='text' name='compressor_serial_number' placeholder='Compressor Serial Number' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='compressor_type' class='label'>Compressor Type &ast;</label>
                            <input required type='text' name='compressor_type' placeholder='Compressor Type' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '></div>
                    </div>

                    <hr>

                    <h2>Dispenser</h2>

                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='dispenser_id' class='label'>Dispenser Id &ast;</label>
                            <input required type='text' name='dispenser_id' placeholder='Dispenser Id' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='dispenser_make' class='label'>Dispenser Make &ast;</label>
                            <input required type='text' name='dispenser_make' placeholder='Dispenser Make' class='input col-12' />
                        </div>
                    </div>

                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                        <label for='dispenser_model' class='label'>Dispenser Model &ast;</label>
                            <input required type='text' name='dispenser_model' placeholder='Dispenser Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='dispenser_type' class='label'>Dispenser Type &ast;</label>
                            <input required type='text' name='dispenser_type' placeholder='Dispenser Type' class='input col-12' />
                        </div>
                    </div>

                    

                    <!-- <h2>Compressor</h2> -->
                    <!-- <div id='compressor'></div> -->


                    <!-- <div id='compressor2' class=''>
                        <h2>Compressor 2</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required name='compressor_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_serial_number' placeholder='Serial Number' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_type' placeholder='Type' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                            </div>
                        </div>
                        <hr>
                    </div>


                    <div id='compressor3' class=''>
                        <h2>Compressor 3</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required name='compressor_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_serial_number' placeholder='Serial Number' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_type' placeholder='Type' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                            </div>
                        </div>
                        <hr>
                    </div>


                    <div id='compressor4' class=''>
                        <h2>Compressor 4</h2>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required name='compressor_id' placeholder='ID' type='text' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_make' placeholder='Make' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_model' placeholder='Model' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_serial_number' placeholder='Serial Number' class='input col-12' />
                            </div>
                        </div>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-5 col-12 '>
                                <input required type='text' name='compressor_type' placeholder='Type' class='input col-12' />
                            </div>
                            <div class='col-lg-5 col-12 '>
                            </div>
                        </div>
                        <hr>
                    </div> -->


                    <!-- <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <input name='bays_count' id='bays_count' placeholder='Number of Bays' type='number' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <div>
                                <input type='number' name='dispenser_count' id='dispenser_count' placeholder='Number of Dispensers per Bay' class='input col-9' />
                                <input type='button' value='Go' class='btn btn-primary col-2' id='add_dispenser_btn' />
                            </div>
                        </div>
                    </div> -->

                    <hr>

                    <!-- <div id='dispenser'>
                    </div> -->

                    <div class='form-buttons col-12'>
                        <input typw='button' value='Reset' class='btn btn-warning cancel-btn' />
                        <button type='button' id='eqp_info_submit' class='btn btn-primary submit-btn'>Submit</button>
                    </div>
                </form>
            </div>

            <div class='ins-info'>
                <!-- action='../CNG_API/master_reg_edit.php?apicall=insertInstrumentInfo'  method='post' -->
                <form id='ins_info_form'  enctype="multipart/form-data" class='reg-form'>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='Station-type'>Select Station Type &ast;</label>
                            <select required name='Station_type' id='ins-station-type' class='input col-12'>
                                <option value='NA'>Select Station Type</option>
                                <option value='City Gas Station'>City Gas Station</option>
                                <option value='Mother Gas Station'>Mother Gas Station</option>
                                <option value='Daughter Booster Station'>Daughter Booster Station</option>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_id'>Select Station ID &ast;</label>
                            <select required name='Station_id' id='ins_station_id' class='input col-12'>
                            </select>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mgsId' class='ins-mgs'>Select Mother Gas Station &ast;</label>
                            <select disabled name='mgsId' class='input col-12' id='ins-mgs'>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='stationary_cascade_id'>Select Stationary Cascade Id &ast;</label>
                            <select name='stationary_cascade_id' data-station_id='' id='instrument_stationary_cascade_id' class='input col-12'>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <h2>Temperature Gauge</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='temperature_gauge_id' class='label'>Temperature Gauge Id &ast;</label>
                            <input required name='temperature_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='temperature_gauge_make' class='label'>Temperature Gauge Make &ast;</label>
                            <input required type='text' name='temperature_gauge_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='temperature_model' class='label'>Temperature Gauge Model &ast;</label>
                            <input required type='text' name='temperature_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label required for='temperature_last_calibration_date'>Calibration Date &ast;</label>
                            <input required type="date" format='dd-mm-yyyy' id="installation-date" name="temperature_last_calibration_date" class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label required for='temperature_claibration_cycle'>Calibration Cycle &ast;</label>
                            <input required type='text' name='temperature_claibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '></div>
                    </div>

                    <hr>
                    
                    <h2>Low Pressure Gauge</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='low_pressure_gauge_id' class='label'>Low Pressure Gauge ID &ast;</label>
                            <input required name='low_pressure_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='low_pressure_gauge_make' class='label'>Low Pressure Gauge Make &ast;</label>
                            <input required type='text' name='low_pressure_gauge_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='low_pressure_gauge_model' class='label'>Low Pressure Gauge Model &ast;</label>
                            <input required type='text' name='low_pressure_gauge_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label required for='low_pressure_gauge_claibration_date'>Low Calibration Date &ast;</label>
                            <input required type="date" format='dd-mm-yyyy' id="low_pressure_gauge_claibration_date" name="low_pressure_gauge_claibration_date" class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label required for='low_pressure_gauge_calibration_cycle'>Low Calibration Cycle &ast;</label>
                            <input required type='text' name='low_pressure_gauge_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'></div>
                    </div>

                    <hr>

                    <h2>Medium Pressure Gauge</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='medium_pressure_gauge_id' class='label'>Pressure Gauge ID &ast;</label>
                            <input required name='medium_pressure_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='medium_pressure_gauge_make' class='label'>Pressure Gauge Make &ast;</label>
                            <input required type='text' name='medium_pressure_gauge_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='medium_pressure_gauge_model' class='label'>Pressure Gauge Model &ast;</label>
                            <input required type='text' name='medium_pressure_gauge_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label required for='medium_pressure_gauge_claibration_date'>Calibration Date &ast;</label>
                            <input required type="date" format='dd-mm-yyyy' id="medium_pressure_gauge_claibration_date" name="medium_pressure_gauge_claibration_date" class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label required for='medium_pressure_gauge_calibration_cycle'>Calibration Cycle &ast;</label>
                            <input required type='text' name='medium_pressure_gauge_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'></div>
                    </div>

                    <hr>

                    <h2>High Pressure Gauge</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='high_pressure_gauge_id' class='label'>Pressure Gauge ID &ast;</label>
                            <input required name='high_pressure_gauge_id' placeholder='ID' type='text' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='high_pressure_gauge_make' class='label'>Pressure Gauge Make &ast;</label>
                            <input required type='text' name='high_pressure_gauge_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='high_pressure_gauge_model' class='label'>Pressure Gauge Model &ast;</label>
                            <input required type='text' name='high_pressure_gauge_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label required for='high_pressure_gauge_claibration_date'>Calibration Date &ast;</label>
                            <input required type="date" format='dd-mm-yyyy' id="high_pressure_gauge_claibration_date" name="high_pressure_gauge_claibration_date" class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label required for='high_pressure_gauge_calibration_cycle'>Calibration Cycle &ast;</label>
                            <input required type='text' name='high_pressure_gauge_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'></div>
                    </div>

                    <hr>

                    <h2>Mass Flow Meter</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='mass_flow_meter_id' class='label'>Mass Flow Meter Id &ast;</label>
                            <input required name='mass_flow_meter_id' placeholder='ID' type='text' class='input col-12' />
                            <span class = 'availability-msg'>available</span>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='mass_flow_make' class='label'>Mass Flow Meter Make &ast;</label>
                            <input required type='text' name='mass_flow_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12 '>
                            <label for='mass_flow_model' class='label'>Mass Flow Meter Model &ast;</label>
                            <input required type='text' name='mass_flow_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='mass_flow_serial_number' class='label'>Serial Number &ast;</label>
                            <input required type='text' name='mass_flow_serial_number' placeholder='Serial Number' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label required for='mass_flow_calibration_date'>Calibration Date &ast;</label>
                            <input required type="date" format='dd-mm-yyyy' id="mfm-installation-date" name="mass_flow_calibration_date" class='input col-12'>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label required for='pressure_gauge_calibration_cycle'>Calibration Cycle &ast;</label>
                            <input required type='text' name='mass_flow_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                    </div>
                    
                    <div class='form-buttons col-12'>
                        <input type='button' value='Reset' id='resetform' class='btn btn-warning cancel-btn' />
                        <button type='button' id='ins_info_sumbit' class='btn btn-primary submit-btn'>Submit</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>





    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script>
        <?php include '../dist/js/register-station.js' ?> 
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <?php include('footer.php'); ?>

</body>
</html>