<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}

include '../CNG_API/conn.php';
// $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
// Fetch the marker info from the database
// $result = mysqli_query($conn, "SELECT * FROM luag_lcv_tracking");

// Fetch the info-window data from the database
// $result2 = mysqli_query($conn, "SELECT * FROM luag_lcv_tracking");




// $conn = mysqli_connect('localhost', 'root', '', '');

// $select_query = "select * from luag_schedule";
// $result = mysqli_query($conn, $select_query);
// $record_count=mysqli_num_rows($result);
// $_SESSION['record_count']=$record_count;


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
    
</head>
<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">

        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white; ">MGS-DBS Routing</h3>
            </div>
        </div>
        <div class='container-fluid' style="background-color: #fff999;">
            <div class='gen-info content-active'>
                <form id='route_details_form' enctype="multipart/form-data" class='reg-form'>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='mgs' class='label col-12'>Select MGS &ast;</label>
                            <select required name='mgs_id' id='mgs_id' class='input col-12'>
                                <option value='NA'>Loading...</option>
                                <?php //include 'partials/_fetch_mgs.php'; ?>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12 '>
                            <label for='dbs' class='label col-12'>Select DBS &ast;</label>
                            <select required name='dbs_id' id='dbs_id' class='input col-12'>
                                <option value='NA'>Loading...</option>
                                <?php //include 'partials/get_all_dbs.php'; ?>
                            </select>
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='route_id' class='label col-12'>Route ID &ast;</label>
                            <input required type='text' name='route_id' placeholder='Route Id' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='route_description' class='label col-12'>Route Description</label>
                            <input required type='text' name='route_description' placeholder='Route Description' class='input col-12' />
                        </div>
                    </div>
                    
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='time_slot' class='label col-12'>Time Slot &ast;</label>
                            <select required name='time_slot' id='time_slot' class='input col-12'>
                                <option value='NA'>Select Time Slot</option>
                                <option value='anytime'>Anytime</option>
                                <option value='6 AM - 10 AM'>6 AM - 10 AM</option>
                                <option value='10 AM - 4 PM'>10 AM - 4 PM</option>
                                <option value='4 PM - 10 PM'>4 PM - 10 PM</option>
                                <option value='10 PM - 6 AM'>10 PM - 6 AM</option>
                            </select>
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='distance' class='label col-12'>Distance(Km) &ast;</label>
                            <input required type='text' name='distance' placeholder='Distance(Km)' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='duration' class='label col-12'>Duration(Hrs) &ast;</label>
                            <input required type='text' name='duration' placeholder='Duration' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='start_coordinates' class='label col-12'>Start Location</label>
                            <input required type='text' name='start_coordinates' placeholder='Start Location' class='input col-12' />
                        </div>
                    </div>
                    <div class='col-12 inp-group'>
                        <div class='col-lg-5 col-12'>
                            <label for='via_coordinates' class='label col-12'>Via Location</label>
                            <input required type='text' name='via_coordinates' placeholder='Via Location' class='input col-12' />
                        </div>
                        <div class='col-lg-5 col-12'>
                            <label for='end_coordinates' class='label col-12'>End Location</label>
                            <input required type='text' name='end_coordinates' placeholder='End Location' class='input col-12' />
                        </div>
                    </div>
                    <!-- <input required type='text' name='via_coordinates' placeholder='Via Location' class='input col-12' />
                    <input required type='text' name='via_coordinates' placeholder='Via Location' class='input col-12' />
                    <input required type='text' name='via_coordinates' placeholder='Via Location' class='input col-12' /> -->

                    <div class='form-buttons col-12'>
                        <input type='button' value='Reset' class='btn btn-warning cancel-btn' />
                        <button type='button' id='route_details_submit' class='btn btn-primary submit-btn'>SUBMIT</button>
                    </div>
                </form>


                <!-- <div id="map"></div> -->
            </div>
        </div>
    </div>
    
    
    <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/fac149b818d7ba75db4aeee2b5e9f70b/map_load?v=1.5"></script>
    

    <script>
        <?php include '../dist/js/mgs_dbs_route.js' ?> 
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <?php include('footer.php'); ?>
    
</body>
</html>


