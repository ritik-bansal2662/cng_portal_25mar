<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}

include '../CNG_API/conn.php';
$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
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
    <style>
        #map, #mapCanvas {
            width: 100%;
            height: 450px;
            /* background: #fff; */
            z-index: 0;
            border: 2px solid #000;
        }
        <?php include '../dist/css/scheduling.css'; ?>
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
    
</head>
<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">

        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white; ">Scheduling</h3>
            </div>
        </div>
        <div class='container-fluid' style="background-color: #fff999;">
            <div  id="update_data" role="dialog">
                <div >
                    <div class="scheduling">
                        <!-- <div class="modal-header">
                            <h5 class="modal-title"><i class="fa fa-edit"></i> Schedule LCV</h5>
                        </div> -->
                        <div class="modal-body text-dark">
                            <!-- action='partials/update_lcv_schedule.php' method="post"  -->
                            <form id='schedule_form' enctype="multipart/form-data" > 
                                <div class='col-12 inp-group'>
                                    <div class='col-lg-6 col-12'>
                                        <!-- <div class="form-group col-12"> -->
                                            <label for="exampleFormControlInput1" class='text-center d-block'>MGS ID(Source)
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <!-- <span class='text-center d-block'> -->
                                                <?php 
                                                    date_default_timezone_set('Asia/Kolkata'); 
                                                    echo date('d-m-Y H:i'); 
                                                ?>
                                            <!-- </span> -->
                                            </label>
                                            <?php
                                            // if($_SESSION['user_role'] == 'Admin') { ?>
                                                <select name='mgsid' id='mgsid' class='input col-12 form-control'>
                                                    <?php 
                                                        $opt="<option value='NA'>Select MGS</option>";
                                                        if($_SESSION['user_role']=='Manager') {
                                                            $opt .="<option value='".$_SESSION['mgs_id']. "'>".$_SESSION['mgs_id']."</option>";
                                                            echo $opt;
                                                            // echo "a";
                                                        } else {
                                                            // include '/partials/_fetch_mgs.php';
                                                            // echo "<option>nothing</option>";
                                                            $select_sql = "select distinct Station_Id, Station_Name from luag_station_master where Station_type = 'Mother Gas Station'";
                                                            $result = mysqli_query($conn, $select_sql);
                                                            $num_rows = mysqli_num_rows($result);
                                                            $output = "<option value='NA'>Select MGS id</option>";
                                                            while($row = $result-> fetch_assoc()) {
                                                                $output .= "<option value='".$row['Station_Id']."'>".$row['Station_Id'] . " - " . $row['Station_Name'] ."</option>";
                                                            }

                                                            echo $output;
                                                        } ?>
                                                </select>
                                            
                                                <!-- <input name='mgsid' id='mgs_id' type='button' value="<?php //echo $_SESSION['mgs_id']; ?>" readonly class='input col-12 form-control' /> -->
                                        <!-- </div> -->
                                    </div>
                                    <div class='col-lg-6 col-12'>
                                        <label for="exampleFormControlInput1" class='text-center d-block'>DBS ID(Destination)</label>
                                        <select class='input col-12 form-control' id="dbsid" name="dbsid">
                                        </select>
                                    </div>
                                </div>
                                <input hidden type="text" class="input form-control" id="latlngMGS" name="latlngMGS">
                                <input hidden type="text" class="input form-control" id="latlngDBS" name="latlngDBS">
                                
                                <div class='col-12 inp-group'>
                                    <div class='col-lg-6 col-12'>
                                        <label for='gas_left_in_dbs' class='text-center d-block'>Gas left in Cascade(in Kg)</label>
                                        <input readonly type="text" class="col-12 input form-control" placeholder='Gas left in Cascade(Read only)' id="gas_left_in_dbs" name="gas_left_in_dbs">
                                    </div>
                                    <div class='col-lg-6 col-12'>
                                    </div>
                                </div>

                                <div class='col-12 inp-group d-flex'>
                                    <!-- <div class='col-lg-6 col-12'> -->
                                        <button type="button" id="calct_dist_time" class="btn btn-primary m-auto" value="Calculate Distance and Time">Calculate Distance and Time</button>
                                    <!-- </div> -->
                                </div>
                                
                                <div class='col-12 inp-group'>
                                    <div class='col-lg-6 col-12'>
                                        <label for='distance' class='text-center d-block'>Distance (in Km)</label>
                                        <input readonly type="text" class="col-12 input form-control" placeholder='Distance' id="distance" name="distance">
                                    </div>
                                    <div class='col-lg-6 col-12'>
                                        <label for='time' class='text-center d-block'>Time (in Hrs)</label>
                                        <input type="text" class="col-12 input form-control" placeholder='Time in hours' id="time" name="time">
                                    </div>
                                </div>
                                <div class='col-12 inp-group'>
                                    <div class='col-lg-6 col-12'>
                                        <label for="exampleFormControlInput1" class='text-center d-block'>LCV ID</label>
                                        <select class='col-12 input form-control' name="lcvid" id="lcvid">
                                        </select>
                                    </div>
                                    <div class='col-lg-6 col-12'>
                                        <label for="exampleFormControlInput1" class='text-center d-block'>LCV Status</label>
                                        <input readonly type="text" class="input form-control" id="lcvstatus" name="lcvstatus" />
                                        <!-- <select required  id="lcvstatus" name="lcvstatus" class='form-control input col-12'>
                                            <option value=NULL>Select LCV Status</option>
                                            <option value='Schedule'>Scheduled</option>
                                            <option value='Transit'>In Transit</option>
                                            <option value='Halt'>At Halt</option>
                                            <option value='Not known'>Not Known</option>
                                        </select> -->
                                    </div>
                                </div>
                                    <!-- <input type="text" class="form-control" id="lcvid" name="lcvid"> -->

                                    <!-- <input type="hidden" name="id_modal" id="id_modal" class="input form-control-sm"> -->
                                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                <div class='col-12 inp-group'>
                                    <button type="button" id="update_detail" class="btn btn-primary input" value="Update">Allocate</button>
                                </div>
                            </form>

                            <div class="col-12 hidden-sm hidden-xs">
                                <!-- <iframe id='scheduling_map' src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3886.6139875875365!2d77.58402861482297!3d13.060225090797745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae1821ca8b1d9f%3A0x8fd257ca32720efd!2sLotus+Value+Developers!5e0!3m2!1sen!2sin!4v1543580574885" width="100%" height="400" frameborder="0" style="border:0"></iframe> -->
                            </div>
                            <div id="map"></div>
                            <!-- <div id="mapCanvas"></div> -->
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/fac149b818d7ba75db4aeee2b5e9f70b/map_load?v=1.5"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASu2stqT8Pf0E108d3RuUcF-f-MVqjUD4"></script> -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&v=weekly" defer></script> -->
    <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&libraries=places"
            type="text/javascript"></script> -->
    

    <script>
        <?php include '../dist/js/scheduling.js' ?> 
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <?php include('footer.php'); ?>
    
</body>
</html>


