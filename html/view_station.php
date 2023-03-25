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

$select_sql = "SELECT * from luag_role_mapping";
$mgs_select_query = "SELECT * from luag_station_master where Station_type = 'Mother Gas Station'";
$dbs_select_query = "SELECT * from luag_station_master where Station_type = 'Daughter Booster Station'";
$cgs_select_query = "SELECT * from luag_station_master where Station_type = 'City Gas Station'";

$mgs_result = mysqli_query($conn, $mgs_select_query);
$dbs_result = mysqli_query($conn, $dbs_select_query);
$cgs_result = mysqli_query($conn, $cgs_select_query);
// print_r($result);
// echo "<br><br><br>";
// $emp_num_rows = mysqli_num_rows($emp_result);

?>


<!DOCTYPE html>
<html lang="en">

<?php include('head.php'); ?>
<style rel='stylesheet'>
    <?php //include '../dist/css/edit-station.css'; ?>
    table {
        padding:2px;
        width:100%;
        overflow-x: scroll;
    }
    tbody#table_body {
        background-color: #f4f8fb;
    }
</style>


<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white;">View Station Details</h3>
            </div>
        </div>

        

        <div class="container-fluid" style="background-color: #f9ffd0;">
            <div class='gen-info d-block'>
                <h3>Mother Gas Station</h3>
                <div class="table-responsive fixTableHead">
                    <table class="table table-striped table-bordered no-wrap mb-0">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col"><strong>S.No</strong></th>
                                <th class="header" scope="col"><strong>Station ID</strong></th>
                                <th class="header" scope="col"><strong>Station Name</strong></th>
                                <th class="header" scope="col"><strong>Notification Approver</strong></th>
                                <th class="header" scope="col"><strong>Station Incharge</strong></th>
                                <th class="header" scope="col"><strong>Station Incharge Contact</strong></th>
                                <th class="header" scope="col"><strong>Filling Bays</strong></th>
                                <th class="header" scope="col"><strong>Dispenser per Bay</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-dark" id='table_body'>
                            <?php
                            $i=1;
                            while($mgs_row = mysqli_fetch_array($mgs_result, MYSQLI_ASSOC)) {
                                // print_r($emp_row);
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $mgs_row['Station_Id']; ?></td>
                                    <td><?php echo $mgs_row['Station_Name']; ?></td>
                                    <td><?php echo $mgs_row['notification_approver_id']; ?></td>
                                    <td><?php echo $mgs_row['Station_In_Charge_Name']; ?></td>
                                    <td><?php echo $mgs_row['Station_In_Charge_Contact_Number']; ?></td>
                                    <td><?php echo $mgs_row['Number_Filling_Bays']; ?></td>
                                    <td><?php echo $mgs_row['Number_Dispenser_Per_Bay']; ?></td>
                                </tr>
                            <?php 
                                $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <hr>
                <br>

                <h3>Daughter Booster Station</h3>
                <div class="table-responsive fixTableHead">
                    <table class="table table-striped table-bordered no-wrap mb-0">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col"><strong>S.No</strong></th>
                                <th class="header" scope="col"><strong>Station ID</strong></th>
                                <th class="header" scope="col"><strong>Station Name</strong></th>
                                <th class="header" scope="col"><strong>MGS ID</strong></th>
                                <th class="header" scope="col"><strong>Notification Approver</strong></th>
                                <th class="header" scope="col"><strong>Station Incharge</strong></th>
                                <th class="header" scope="col"><strong>Station Incharge Contact</strong></th>
                                <th class="header" scope="col"><strong>Filling Bays</strong></th>
                                <th class="header" scope="col"><strong>Dispenser per Bay</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-dark" id='table_body'>
                            <?php
                            $i=1;
                            while($dbs_row = mysqli_fetch_array($dbs_result, MYSQLI_ASSOC)) {
                                // print_r($emp_row);
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $dbs_row['Station_Id']; ?></td>
                                    <td><?php echo $dbs_row['Station_Name']; ?></td>
                                    <td><?php echo $dbs_row['mgsId']; ?></td>
                                    <td><?php echo $dbs_row['notification_approver_id']; ?></td>
                                    <td><?php echo $dbs_row['Station_In_Charge_Name']; ?></td>
                                    <td><?php echo $dbs_row['Station_In_Charge_Contact_Number']; ?></td>
                                    <td><?php echo $dbs_row['Number_Filling_Bays']; ?></td>
                                    <td><?php echo $dbs_row['Number_Dispenser_Per_Bay']; ?></td>
                                </tr>
                            <?php 
                                $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>

                <br>
                <hr>
                <br>

                <h3>City Gas Station</h3>
                <div class="table-responsive fixTableHead">
                    <table class="table table-striped table-bordered no-wrap mb-0">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col"><strong>S.No</strong></th>
                                <th class="header" scope="col"><strong>Station ID</strong></th>
                                <th class="header" scope="col"><strong>Station Name</strong></th>
                                <th class="header" scope="col"><strong>Notification Approver</strong></th>
                                <th class="header" scope="col"><strong>Station Incharge</strong></th>
                                <th class="header" scope="col"><strong>Station Incharge Contact</strong></th>
                                <th class="header" scope="col"><strong>Filling Bays</strong></th>
                                <th class="header" scope="col"><strong>Dispenser per Bay</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-dark" id='table_body'>
                            <?php
                            $i=1;
                            while($cgs_row = mysqli_fetch_array($cgs_result, MYSQLI_ASSOC)) {
                                // print_r($emp_row);
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $cgs_row['Station_Id']; ?></td>
                                    <td><?php echo $cgs_row['Station_Name']; ?></td>
                                    <td><?php echo $cgs_row['notification_approver_id']; ?></td>
                                    <td><?php echo $cgs_row['Station_In_Charge_Name']; ?></td>
                                    <td><?php echo $cgs_row['Station_In_Charge_Contact_Number']; ?></td>
                                    <td><?php echo $cgs_row['Number_Filling_Bays']; ?></td>
                                    <td><?php echo $cgs_row['Number_Dispenser_Per_Bay']; ?></td>
                                </tr>
                            <?php 
                                $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>






            


        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script>
        <?php // include '../dist/js/edit-station.js' ?> 
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