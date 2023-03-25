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
    <?php include('../dist/css/lcv_status.css'); ?>
</style>

<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h3 style="color:white;">LCV Status and Live Positioning</h3>
            </div>
        </div>

        <div class="container-fluid" style="background-color: #fff999;">
            <div class='gen-info content-active'>

                <div class='col-12 inp-group'>
                    <div class="col-lg-5 col-12">
                        <label for='lcv_number'>Select LCV</label>
                        <select name='lcv_number' id='lcv_number' class='input col-12'>
                            <?php 
                                // $lcv_select_sql = "SELECT Lcv_Num from reg_lcv";
                                $lcv_select_sql = "SELECT Notification_LCV as Lcv_Num from notification group by Notification_LCV";
                                $lcv_result = mysqli_query($conn, $lcv_select_sql );
                                $lcv_num_rows = mysqli_num_rows($lcv_result );
                                $lcv_output='';
                                if($lcv_num_rows > 0) {
                                    $lcv_output.= "<option value='NA'>-- No Selection --</option>";
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
                    <div class="col-lg-5 col-12">
                        <label for='lcv_status_date' class='col-12'>Date</label>
                        <input type="date" format='yyyy-mm-dd' id="lcv_status_date" name="lcv_status_date" class='input col-8'>
                        <button id='lcv_status_filter' type='button' name='lcv_status_filter' class='btn btn-primary m-2 class-3'>Go</button>
                    </div>
                </div>

                <hr />
                
                <div class="d-flex justify-content-center">
                    <!-- <button type='button' id='today-btn' class='btn btn-primary m-2'>Today</button> -->
                    <button type='button' id='refresh-btn' class='btn btn-primary m-2'>Refresh</button>
                    <button type='button' id='download-table' class='btn btn-primary m-2'>Download</button>
                    <button type='button' id='full-screen-btn' class='btn btn-primary m-2'>Full Screen</button>
                </div>

                <hr />
                <h2 class='loading' id='loading_table'>Loading...</h2>
                <h2 class='error' id='error_table'>No Data Available</h2>
                <div class="table-responsive fixTableHead">
                    <table id='lcv-table-data' class="table table-striped table-bordered no-wrap mb-0">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col" rowspan="2"><strong>S.No</strong></th>
                                <th class="header" scope="col" rowspan="2"><strong>LCV Number</strong></th>
                                <th class="header" scope="col" rowspan="2"><strong>Date</strong></th>
                                <th class="header" scope="col" rowspan="2"><strong>MGS</strong></th>
                                <th class="header" scope="col" rowspan="2"><strong>DBS</strong></th>
                                <th class="header" scope="col"><strong>Stage 1 <br> Vehicle Entered MGS </strong></th>
                                <th class="header" scope="col"><strong>Stage 2 <br> Vehicle in Safe Zone at MGS </strong></th>
                                <th class="header" scope="col"><strong>Stage 3 <br> Vehicle filled and leaving MGS </strong></th>
                                <th class="header" scope="col"><strong>Live Tracking <br> Vehicle in transit(MGS to DBS) </strong></th>
                                <th class="header" scope="col"><strong>Stage 4 <br> Vehicle Entered DBS </strong></th>
                                <th class="header" scope="col"><strong>Stage 5 <br> Vehicle in Safe Zone at DBS </strong></th>
                                <th class="header" scope="col"><strong>Stage 6 <br> Vehicle emptied and leaving DBS </strong></th>
                                <th class="header" scope="col"><strong>Live Tracking <br> Vehicle in transit(DBS to MGS) </strong></th>
                            </tr>
                            <!-- <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col"><strong>Vehicle Entered MGS</strong></th>
                                <th class="header" scope="col"><strong>Vehicle in Safe Zone at MGS</strong></th>
                                <th class="header" scope="col"><strong>Vehicle filled and leaving MGS</strong></th>
                                <th class="header" scope="col"><strong>Vehicle in transit(MGS to DBS)</strong></th>
                                <th class="header" scope="col"><strong>Vegicle Entered DBS</strong></th>
                                <th class="header" scope="col"><strong>Vehicle in Safe Zone at DBS</strong></th>
                                <th class="header" scope="col"><strong>Vehicle emptied and leaving DBS</strong></th>
                                <th class="header" scope="col"><strong>Vehicle in transit(DBS to MGS)</strong></th>
                            </tr> -->
                        </thead>
                        <tbody class="text-dark" id='table_body'>
                        </tbody>
                    </table>
                </div>

                <div id="map-popup" class='map-popup'>
                    <div class="overlay"></div>
                    <div class="content">
                        <div class='close-btn' id='close-btn' onClick="tooglePopup()">&times;</div>
                        <h2 id='tracking_status'></h2>

                        <h3>LCV Number: <span id='lcv_num'></span> | Vendor: <span id='lcv-vendor'></span></h3>
                        
                        <hr />

                        <div class="trip-details d-flex flex-wrap">
                            <div class="from-details col-lg-6 col-12">
                                <h3 class='text-left'>From</h3>
                                <div>Station ID: <span id='from_station'></span></div>
                                <div>Station Address: <span id='from-station-address'></span></div>
                                <div>Date: <span id='from_date'></span></div>
                            </div>
                            <div class="to-details col-lg-6 col-12">
                                <h3 class='text-left'>To</h3>
                                <div>Station ID: <span id='to_station'></span></div>
                                <div>Station Address: <span id='to-station-address'></span></div>
                                <div>Date: <span id='to_date'></span></div>
                            </div>
                        </div>

                        <hr />

                        <h2 id='loading-map' class='loading'>Loading...</h2>

                        <h2 class='error' id='error-map'>No Tracking Data Available</h2>
                        
                        <h2 class='vehicle-moving-status justify-content-center' id='vehicle-moving-status'>Vehicle At Halt</h2>
                        
                        <div id="map">
                            <div>Loading Map...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script> -->
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/fac149b818d7ba75db4aeee2b5e9f70b/map_load?v=1.5"></script>
    <script type='text/javascript' src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <script>
        <?php 
            include '../dist/js/lcv_status.js';
        ?> 
        // function exportReportToExcel() {
        //     let table = $("table");
        //     TableToExcel.convert(table[0], { 
        //         name: `lcv_table.xlsx`,
        //         sheet: {
        //             name: 'Sheet 1'
        //         }
        //     });
        // }
    </script>

    <script src="../dist/js/table2excel.js"></script>

    <script>
        $('#download-table').click(function() {
            var table2excel = new Table2Excel();
            table2excel.export($("#lcv-table-data"));
        })
    </script>
    
    <script>
        $("#lcv-table-data").ddTableFilter();
    </script>


    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <?php include('footer.php'); ?>

</body>
</html>

