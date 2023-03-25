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

?>


<!DOCTYPE html>
<html lang="en">

<?php include('head.php'); ?>
<style rel='stylesheet'>
    <?php include '../dist/css/edit-station.css'; ?>
</style>


<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white;">Edit Station Details</h3>
            </div>
            
            <div class='reg-main'>
                <div class='gen col-4 main-active'>General Information</div>
                <div class='eqp col-4'>Equipment Information</div>
                <div class='ins col-4'>Instruments Information</div>
            </div>
        </div>

        <!-- for extra space -->
        <div class="top-temp-extra"></div> 

        <div class="container-fluid" style="background-color: #fff999;">
            <div class='gen-info content-active'>
                <!-- method='post' action='../CNG_API/master_reg_edit.php?apicall=updateGenInfo' -->
                <form id='gen_edit_form' class='reg-form'>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_type'>Select Station Type</label>
                            <select name='Station_type' class='input col-12' id='gen-station-type'>
                                <option>Select Station Type</option>
                                <option>City Gas Station</option>
                                <option>Mother Gas Station</option>
                                <option>Daughter Booster Station</option>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_id'>Select Station Id</label>
                            <select name='Station_id' class='input col-12' id='gen_station_id' >
                            </select>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mgsId' class='mgs gen_mgsId'>Select MGS ID</label>
                            <select name='mgsId' id='gen_mgsId' placeholder='MGS ID' type='text' class='mgs input col-12'>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'></div>
                        <!-- <input type='text' placeholder='' class='input extra col-lg-5 col-12' readonly /> -->
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_Name' class='label'>Name of Gas Station</label>
                            <input readonly type='text' name='Station_Name' id='Station_Name' placeholder='Name of Station' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_Address' class='label'>Station Address</label>
                            <input type='text' name='Station_Address' id='Station_Address' placeholder='Address line 1' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_In_Charge_Name' class='label'>Name of Gas Station Incharge</label>
                            <input type='text' name='Station_In_Charge_Name' id='Station_In_Charge_Name' placeholder='Name of Gas Station Incharge' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_In_Charge_Contact_Number' class='label'>Contact Number of Incharge</label>
                            <input type='number' name='Station_In_Charge_Contact_Number' id='Station_In_Charge_Contact_Number' placeholder='Contact Number of Incharge' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='Number_Filling_Bays' class='label'>Number of Filing Bays</label>
                            <input type='number' name='Number_Filling_Bays' id='Number_Filling_Bays' placeholder='Number of Filing Bays' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='Number_Dispenser_Per_Bay' class='label'>Number of Dispenser per Bays</label>
                            <input type='number' name='Number_Dispenser_Per_Bay' id='Number_Dispenser_Per_Bay' placeholder='Number of Dispenser per Bays' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <div class='location-gps color-brown' onclick="getLocation()">Click here to Locate station through GPS</div>
                            <input class='input col-12' name='Latitude_Longitude' id='Latitude_Longitude' placeholder='Latitide and Longitude' />
                        </div>
                        <div class='col-lg-5 col-12'></div>
                        <!-- <input type='text' placeholder='' id='' class='input extra col-lg-5 col-12' readonly /> -->
                    </div>
                    
                    <div class='form-buttons col-12'>
                        <input class='btn btn-warning cancel-btn' value='Reset' />
                        <button type='button' id='gen_edit_submit' class='btn btn-primary submit-btn'>Update</button>
                    </div>
                </form>
            </div>

            <div class='eqp-info'>
                <!-- method='post' action='../CNG_API/master_reg_edit.php?apicall=updateEquipInfo' -->
                <form id='eqp_edit_form' class='reg-form'>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_type'>Select Station Type</label>
                            <select name='Station_type' id='eqp-station-type' class='input col-12'>
                                <option value='NA'>Select Station Type</option>
                                <option>City Gas Station</option>
                                <option>Mother Gas Station</option>
                                <option>Daughter Booster Station</option>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_id'>Select Station ID</label>
                            <select name='Station_id' class='input col-9' id='eqp-station-id'>
                            </select>
                            <button type='button' id='get_eqp_details' class='btn btn-primary col-2'>Go</button>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mgsId' class='mgs eqp_mgsId'>Select MGS ID</label>
                            <select name='mgsId' id='eqp_mgsId' placeholder='MGS ID' type='text' class='mgs input col-12'>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'></div>
                        <!-- <input type='text' placeholder='' class='input extra col-lg-5 col-12' readonly /> -->
                    </div>

                    <hr>

                    <h2>Stationary Cascade</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_id' class='label'>Stationary Cascade ID</label>
                            <input name='stationary_cascade_id' id='stationary_cascade_id' placeholder='ID' type='text' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_make' class='label'>Stationary Cascade Make</label>
                            <input type='text' name='stationary_cascade_make' id='stationary_cascade_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_model' class='label'>Stationary Cascade Model</label>
                            <input type='text' name='stationary_cascade_model' id='stationary_cascade_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_serial_number' class='label'>Stationary Cascade Serial Number</label>
                            <input type='text' name='stationary_cascade_serial_number' id='stationary_cascade_serial_number' placeholder='Serial Number' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_reorder_point' class='label'>Stationary Cascade Reorder Point</label>
                            <input name='stationary_cascade_reorder_point' id='stationary_cascade_reorder_point' placeholder='Cascade Reorder Point' type='number' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_installation_date'>Date Of Installation</label>
                            <input type="date" format='dd-mm-yyyy' id="stationary_cascade_installation_date" name="stationary_cascade_installation_date" class='input col-12'>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_hydrotest_status_date'>Hydro Status Date</label>
                            <input type="date" format='dd-mm-yyyy' id="stationary_cascade_hydrotest_status_date" name="stationary_cascade_hydrotest_status_date" class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_hydrotest_status' class='label'>Hydrotest Status</label>
                            <input type='number' name='stationary_hydrotest_status' id='stationary_hydrotest_status' placeholder='Hydrotest Status' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='stationary_cascade_capacity' class='label'>Capacity of Stationary Cascade</label>
                            <input type='text' name='stationary_cascade_capacity' id='stationary_cascade_capacity' placeholder='Capacity of Stationary Cascade' class='input col-12' />
                        </div>
                    </div>

                    <hr>

                    <h2>Compressor</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='compressor_id' class='label'>Compressor Id</label>
                            <input name='compressor_id' id='compressor_id' placeholder='ID' type='text' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='compressor_make' class='label'>Compressor Make</label>
                            <input type='text' name='compressor_make' id='compressor_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='compressor_model' class='label'>Compressor Model</label>
                            <input type='text' name='compressor_model' id='compressor_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='compressor_serial_number' class='label'>Compressor Serial Number</label>
                            <input type='text' name='compressor_serial_number' id='compressor_serial_number' placeholder='Serial Number' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='compressor_type' class='label'>Compressor Type</label>
                            <input type='text' name='compressor_type' id='compressor_type' placeholder='Type' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                        </div>
                    </div>

                    <hr>

                    <h2>Dispenser</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='dispenser_id' class='label'>Dispenser Id</label>
                            <input name='dispenser_id' id='dispenser_id' placeholder='ID' type='text' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='dispenser_make' class='label'>Dispenser Make</label>
                            <input type='text' name='dispenser_make' id='dispenser_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='dispenser_model' class='label'>Dispenser Model</label>
                            <input type='text' name='dispenser_model' id='dispenser_model' placeholder='Model Number' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='dispenser_type' class='label'>Dispenser Type</label>
                            <input type='text' name='dispenser_type' id='dispenser_type' placeholder='Type' class='input col-12' />
                        </div>
                    </div>

                    <div class='form-buttons col-12'>
                        <button class='btn btn-warning cancel-btn'>Reset</button>
                        <button type='button' id='eqp_edit_submit' class='btn btn-primary submit-btn'>Update</button>
                    </div>
                </form>
            </div>

            <div class='ins-info'>
                <!-- method='post' action='../CNG_API/master_reg_edit.php?apicall=updateInstrumentInfo' -->
                <form id='ins_edit_form' class='reg-form'>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_type'>Select Station Type</label>
                            <select name='Station_type' id='ins-station-type' class='input col-12'>
                                <option value=NULL>Select Station Type</option>
                                <option>City Gas Station</option>
                                <option>Mother Gas Station</option>
                                <option>Daughter Booster Station</option>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='Station_id'>Select Station ID</label>
                            <select name='Station_id' id='ins-station-id' class='input col-12'>
                            </select>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mgsId' class='mgs ins_mgsId'>Select MGS ID</label>
                            <select name='mgsId' id='ins_mgsId' placeholder='MGS ID' type='text' class='mgs input col-12'>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'></div>
                        <!-- <input type='text' placeholder='' class='input extra col-lg-5 col-12' readonly /> -->
                    </div>

                    <hr>

                    <h2>Temperature Gauge</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='temperature_gauge_id' class='label'>Temperature Gauge Id</label>
                            <input name='temperature_gauge_id' id='temperature_gauge_id' placeholder='ID' type='text' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='temperature_gauge_make' class='label'>Temperature Gauge Make</label>
                            <input type='text' name='temperature_gauge_make' id='temperature_gauge_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='temperature_model' class='label'>Temperature Gauge Model</label>
                            <input type='text' name='temperature_model' id='temperature_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='temperature_last_calibration_date' class='label'>Calibrated Date</label>
                            <input type="date" format='dd-mm-yyyy' id="installation-date" name="temperature_last_calibration_date" id='temperature_last_calibration_date' class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='temperature_claibration_cycle' class='label'>Calibrated Cycle</label>
                            <input type='text' name='temperature_claibration_cycle' id='temperature_claibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                        </div>
                    </div>

                    <hr>
                    
                    <h2>Pressure Gauge</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='pressure_gauge_id' class='label'>Pressure Gauge Id</label>
                            <input name='pressure_gauge_id' id='pressure_gauge_id' placeholder='ID' type='text' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='pressure_gauge_make' class='label'>Pressure Gauge Make</label>
                            <input type='text' name='pressure_gauge_make' id='pressure_gauge_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='pressure_gauge_model' class='label'>Pressure Gauge Model</label>
                            <input type='text' name='pressure_gauge_model' id='pressure_gauge_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='pressure_gauge_claibration_date' class='label'>Calibrated Date</label>
                            <input type="date" format='dd-mm-yyyy' name="pressure_gauge_claibration_date" id='pressure_gauge_claibration_date' class='input col-12'>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='pressure_gauge_calibration_cycle' class='label'>Calibrated Cycle</label>
                            <input type='text' name='pressure_gauge_calibration_cycle' id='pressure_gauge_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                        </div>
                    </div>
                    <hr>
                    <h2>Mass Flow Meter</h2>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mass_flow_meter_id' class='label'>Mass Flow Meter Id</label>
                            <input name='mass_flow_meter_id' id='mass_flow_meter_id' placeholder='ID' type='text' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='mass_flow_make' class='label'>Mass Flow Meter Make</label>
                            <input type='text' name='mass_flow_make' id='mass_flow_make' placeholder='Make' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mass_flow_make' class='label'>Mass Flow Meter Make</label>
                            <input type='text' name='mass_flow_model' id='mass_flow_model' placeholder='Model' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='mass_flow_serial_number' class='label'>Mass Flow Meter Serial Number</label>
                            <input type='text' name='mass_flow_serial_number' id='mass_flow_serial_number' placeholder='Serial Number' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mass_flow_calibration_date' class='label'>Calibrated Date</label>
                            <input type="date" format='dd-mm-yyyy' name="mass_flow_calibration_date" id='mass_flow_calibration_date' class='input col-12'>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='mass_flow_calibration_cycle' class='label'>Calibratied Cycle</label>
                            <input type='text' name='mass_flow_calibration_cycle' id='mass_flow_calibration_cycle' placeholder='Calibration Cycle' class='input col-12' />
                        </div>
                    </div>
                        
                    <div class='form-buttons col-12'>
                        <button type='button' class='btn btn-warning cancel-btn'>Reset</button>
                        <button type='button' id='ins_edit_submit' class='btn btn-primary submit-btn'>Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script>
        <?php include '../dist/js/edit-station.js' ?> 
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <?php include('footer.php'); ?>
    <script>

        //General
        function genStationId(id) {
            stId=id
            var req= new XMLHttpRequest()
            req.open("GET", "http://localhost/apc/gas-dashboard/CNG_API/read_master_gen.php?id="+stId, true);

            req.onreadystatechange=function() {
                if(req.readyState==4 && req.status==200) {
                    var res = req.responseText;
                    myObj = JSON.parse(res);
                    for(x in myObj) {
                        document.getElementById(myObj[x].name).value=myObj[x];
                    }
                }
            }
        }
        function genStationType(type) {
            stType=type
            var req= new XMLHttpRequest()
            req.open("GET", "http://localhost/apc/gas-dashboard/modules/edit-station/read_station.php?type="+stType, true);

            req.onreadystatechange=function() {
                if(req.readyState==4 && req.status==200) {
                    document.getElementById('gen-Station_id').innerHTML=req.responseText;
                }
            }
        }

        //Instrument
        function insStationId(Id) {
            stId=Id
            var req= new XMLHttpRequest()
            req.open("GET", "http://localhost/apc/gas-dashboard/CNG_API/read_master_instrument_info.php?id="+stId+"&type="+stId, true);

            req.onreadystatechange=function() {
                if(req.readyState==4 && req.status==200) {
                    var res = req.responseText;
                    myObj = JSON.parse(res);
                    for(x in myObj) {
                        document.getElementById(myObj[x].name).value=myObj[x];
                    }
                }
            }
        }

        function insStationType(type) {
            stType=type
            var req= new XMLHttpRequest()
            req.open("GET", "http://localhost/apc/gas-dashboard/modules/edit-station/read_station.php?type="+stType, true);
            
            req.onreadystatechange=function() {
                if(req.readyState==4 && req.status==200) {
                    document.getElementById('ins-Station-id').innerHTML=req.responseText;
                    
                }
            }
        }

        //Equipment
        function eqpStationId(id) {
            stId=Id
            var req= new XMLHttpRequest()
            req.open("GET", "http://localhost/apc/gas-dashboard/CNG_API/read_master_equipment_info.php?id="+stId, true);

            req.onreadystatechange=function() {
                if(req.readyState==4 && req.status==200) {
                    var res = req.responseText;
                    myObj = JSON.parse(res);
                    for(x in myObj) {
                        document.getElementById(myObj[x].name).value=myObj[x];
                    }
                }
            }
        }

        function eqpStationType(type) {
            stType=type
            var req= new XMLHttpRequest()
            req.open("GET", "http://localhost/apc/gas-dashboard/modules/edit-station/read_station.php?type="+stType, true);

            req.onreadystatechange=function() {
                if(req.readyState==4 && req.status==200) {
                    document.getElementById('eqp-Station-id').innerHTML=req.responseText;
                }
            }
        }
            

    </script>

</body>
</html>