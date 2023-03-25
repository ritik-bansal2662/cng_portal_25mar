<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}


include('../CNG_API/conn.php');
$query = mysqli_query($conn, "SELECT date(a.create_date) cas_date,time(a.create_date) cas_time,
a.station_id, a.mass_of_gas,b.`stationary_cascade_reorder_point` 
FROM luag_schedular a,luag_station_equipment_master b 
WHERE a.`sl_no` IN (SELECT MAX(`sl_no`) FROM luag_schedular GROUP BY `station_id`) 
and a.station_id=b.`station_id`");
?>
<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<style>
    .top-temp{
        height: 80px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.0-beta.3/iconify-icon.min.js"></script>


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">

        <!-- <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center text-white">
                    <h2 ">Dashboard</h2>

                </div>

            </div>
        </div> -->

        <div class=" container-fluid">

            <div class="card  text-center h3">
                <div class="card-body text-dark"> LCV Status</div>
            </div>

               

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <!-- Column -->
                                <div class="col-md-6 col-lg-3 col-xlg-3">
                                    <div class="card card-hover" style="border-radius: 20%">
                                        <div class="p-1 bg-primary text-center">
                                            <iconify-icon icon="fluent:vehicle-truck-16-filled" style="color: white;" width="50"></iconify-icon>
                                            <h1 class="font-light text-white">
                                            <?php 
                                                $result = mysqli_query($conn, "SELECT count(*) total FROM `reg_lcv`") or die(mysqli_error($conn));
                                                    $data = mysqli_fetch_assoc($result);
                                                ?> <?php echo $data['total'];    ?>
                                            
                                            </h1>
                                            <h6 class="text-white">Total LCV</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-2 col-xlg-2">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-success text-center">
                                            <iconify-icon icon="fa6-solid:truck-fast" style="color: white;" width="50"></iconify-icon>
                                            <h1 class="font-light text-white">
                                            <?php 
                                                $result = mysqli_query($conn, "SELECT count(*) total FROM `reg_lcv` WHERE lcv_status = 'Transit'") or die(mysqli_error($conn));
                                                    $data = mysqli_fetch_assoc($result);
                                                ?> <?php echo $data['total'];    ?>
                                            
                                            </h1>
                                            <h6 class="text-white">LCV in Transit</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-2 col-xlg-2">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-secondary text-center">

                                            <iconify-icon icon="fa6-solid:truck-droplet" style="color: white;" width="50"></iconify-icon>
                                            <h1 class="font-light text-white">
                                                <?php 
                                                $result = mysqli_query($conn, "SELECT count(*) total FROM `reg_lcv` WHERE lcv_status = 'Filling'") or die(mysqli_error($conn));
                                                    $data = mysqli_fetch_assoc($result);
                                                ?> <?php echo $data['total'];    ?>
                                            </h1>
                                            <h6 class="text-white">LCV Filling</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-2 col-xlg-2">
                                    <div class="card card-hover">
                                        <!-- <div class="p-2 btn-warning text-center"> -->
                                        <div class="p-2 text-center" style='background-color:#FFBF00;'>

                                            <iconify-icon icon="fa-solid:truck-moving" style="color: white;" width="50"></iconify-icon>
                                            <h1 class="font-light text-white">
                                            <?php 
                                                $result = mysqli_query($conn, "SELECT count(*) total FROM `reg_lcv` WHERE lcv_status = 'Emptying'") or die(mysqli_error($conn));
                                                    $data = mysqli_fetch_assoc($result);
                                                ?> <?php echo $data['total'];    ?>
                                            </h1>
                                            <h6 class="text-white">LCV Emptying</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-2 col-xlg-2">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-danger text-center">
                                            <iconify-icon icon="fa-solid:truck-moving" style="color: white;" width="50"></iconify-icon>
                                            <h1 class="font-light text-white">
                                            <?php 
                                                $result = mysqli_query($conn, "SELECT count(*) total FROM `reg_lcv` WHERE lcv_status = 'Halt'") or die(mysqli_error($conn));
                                                    $data = mysqli_fetch_assoc($result);
                                                ?> <?php echo $data['total'];    ?>
                                            </h1>
                                            <h6 class="text-white">LCV Stopped</h6>
                                        </div>
                                    </div>
                                </div>

                                <!-- Column -->
                            </div>
                        </div>
                    </div>
                    <div class="card  text-center h3">
                        <div class="card-body text-dark"> Gas status in Stationary Cascade at DBS</div>
                    </div>
                    <div class="table-responsive fixTableHead mb-3 p-0">
                        <table id="zero_config" class="table table-striped table-bordered no-wrap" id="summary">
                            <thead>
                                <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                    <th class="header" scope="col"><strong>Slno</strong></th>
                                    <th class="header" scope="col"><strong>Date</strong></th>
                                    <th class="header" scope="col"><strong>Time</strong></th>

                                    <th class="header" scope="col"><strong>Station ID</strong></th>
                                    <th class="header" scope="col"><strong>Gas Reorder Point</strong></th>

                                    <th class="header" scope="col"><strong>Gas in Cascade</strong></th>
                                    <th class="header" scope="col"><strong>Auto Scheduling</strong></th>
                                    <th class="header" scope="col"><strong>Status</strong></th>

                                </tr>
                            </thead>
                            <tbody class="text-dark">
                                <?php
                                // $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");

                                // $stmt = $db->prepare("SELECT date(create_date) cas_date,time(create_date) cas_time,station_id, mass_of_gas FROM luag_schedular WHERE `sl_no` IN (SELECT MAX(`sl_no`) FROM luag_schedular GROUP BY `station_id`)");
                                // $stmt = $db->prepare("SELECT date(a.create_date) cas_date,time(a.create_date) cas_time,
                                // a.station_id, a.mass_of_gas,b.`stationary_cascade_reorder_point` 
                                // FROM luag_schedular a,luag_station_equipment_master b 
                                // WHERE a.`sl_no` IN (SELECT MAX(`sl_no`) FROM luag_schedular GROUP BY `station_id`) 
                                // and a.station_id=b.`station_id`");
                                // $stmt->execute();

                                // $lcv_status = '';
                                $i = 1;
                                // while ($row = $stmt->fetch()) {
                                while ($row = mysqli_fetch_array($query)) {
                                    $station_id = $row["station_id"];
                                    $mass_of_gas = $row["mass_of_gas"];
                                    $record_point = intval($row["stationary_cascade_reorder_point"]);
                                    $above_threshold = intval(100);
                                    $query1 = mysqli_query($conn, "SELECT `lcv_status` FROM luag_transaction_master_dbs_station where `dbs_station_id`='$station_id' order by `sl_no` desc limit 1");
                                    $lcv_status='';
                                    while ($row1 = mysqli_fetch_array($query1)) {
                                        $lcv_status = $row1["lcv_status"];
                                        $lcv_status = strtok($lcv_status,  ' ');
                                    }
                                    if ($mass_of_gas < $record_point) {
                                        echo "<tr style='background-color:red;'>"; ?>
                                        <tr class="alert alert-danger fade show">
                                            <td>
                                                <?php echo $i; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["cas_date"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["cas_time"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["station_id"]; ?>
                                            </td>
                                            <td> <?php echo  $record_point; ?></td>

                                            <td>
                                                <?php echo $row["mass_of_gas"]; ?>
                                            </td>
                                            <td><?php echo $lcv_status; ?>
                                            </td>
                                            <!-- <td><a type="button" class="btn btn-info btn-xs edit_data" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#update_data" data-id="<?= $row['cas_date']; ?>" data-title="<?= $row['station_id']; ?>" data-description="<?= $row['cas_time']; ?>" data-uploaded_on="<?= $row['mass_of_gas']; ?>">Edit</a></td> -->
                                            <!-- <td><input type="button" name="view" value="view" id="<?php echo $row["station_id"]; ?>" class="btn btn-info btn-xs view_data" /></td> -->

                                            <td><a type="button" class="btn btn-info btn-xs edit_data text-white" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#update_data" data-id="<?= $row['cas_date']; ?>" data-title="<?= $row['station_id']; ?>" data-description="<?= $row['cas_time']; ?>" data-uploaded_on="<?= $row['mass_of_gas']; ?>"><i class="fa fa-edit"></i>Schedule</a></td>
                                        </tr>
                                    <?php } else if ($mass_of_gas < $record_point + $above_threshold && $mass_of_gas > $record_point) {
                                        echo "<tr style='background-color:#FFBF00;'>"; ?>
                                        <tr style='background-color:#FFBF00;'>
                                            <td>
                                                <?php echo $i; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["cas_date"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["cas_time"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["station_id"]; ?>
                                            </td>
                                            <td> <?php echo  $record_point; ?></td>
                                            <td>
                                                <?php echo $row["mass_of_gas"]; ?>
                                            </td>
                                            <td><?php echo $lcv_status; ?>
                                            </td>
                                            <!-- <td><a type="button" class="btn btn-info btn-xs edit_data" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#update_data" data-id="<?= $row['cas_date']; ?>" data-title="<?= $row['station_id']; ?>" data-description="<?= $row['cas_time']; ?>" data-uploaded_on="<?= $row['mass_of_gas']; ?>">Edit</a></td> -->
                                            <!-- <td><input type="button" name="view" value="view" id="<?php echo $row["station_id"]; ?>" class="btn btn-info btn-xs view_data" /></td> -->

                                            <td><a type="button" class="btn btn-info btn-xs edit_data text-white" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#update_data" data-id="<?= $row['cas_date']; ?>" data-title="<?= $row['station_id']; ?>" data-description="<?= $row['cas_time']; ?>" data-uploaded_on="<?= $row['mass_of_gas']; ?>"><i class="fa fa-edit"></i>Schedule</a></td>
                                            </td>
                                        </tr>
                                    <?php } else if ($mass_of_gas > $record_point + $above_threshold) {
                                        echo "<tr style='background-color:powderblue;'>"; ?>
                                        <tr class="alert alert-success alert-dismissible  fade show">
                                            <td>
                                                <?php echo $i; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["cas_date"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["cas_time"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["station_id"]; ?>
                                            </td>
                                            <td> <?php echo  $record_point; ?></td>

                                            <td>
                                                <?php echo $row["mass_of_gas"]; ?>
                                            </td>
                                            <td><?php echo $lcv_status; ?>
                                            </td>
                                            <!-- <td><a type="button" class="btn btn-info btn-xs edit_data" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#update_data" data-id="<?= $row['cas_date']; ?>" data-title="<?= $row['station_id']; ?>" data-description="<?= $row['cas_time']; ?>" data-uploaded_on="<?= $row['mass_of_gas']; ?>">Edit</a></td> -->
                                            <!-- <td><input type="button" name="view" value="view" id="<?php echo $row["station_id"]; ?>" class="btn btn-info btn-xs view_data" /></td> -->

                                            <td><a type="button" class="btn btn-info btn-xs edit_data text-white" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#update_data" data-id="<?= $row['cas_date']; ?>" data-title="<?= $row['station_id']; ?>" data-description="<?= $row['cas_time']; ?>" data-uploaded_on="<?= $row['mass_of_gas']; ?>"><i class="fa fa-edit"></i>Schedule</a></td>
                                        </tr>
                                <?php   }
                                    $i++;
                                }
                                ?>
                            </tbody>

                        </table>

                    </div>


                    <!-- ############################################################################################### -->
                    <!-- Update data-->
                    <div class="modal fade" id="update_data" role="dialog">
                        <div class="modal-dialog modal-md ">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fa fa-edit"></i> Schedule LCV</h5>
                                </div>
                                <div class="modal-body text-dark">

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">MGS ID(Source)</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php date_default_timezone_set('Asia/Kolkata');
                                                                                                                        echo date('d-m-Y H:i'); ?></span>
                                        <input readonly type="text" class="input ml-0 col-12" id="mgsid" name="mgsid">
                                    </div>
                                    <input hidden type="text" class="input ml-0 col-12" id="latlngDBS" name="latlngDBS">

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">DBS ID(Destination)</label>
                                        <input readonly type="text" class="input ml-0 col-12" id="dbsid" name="dbsid">
                                    </div>

                                    <div class="form-group">
                                        <label for='gas_left_in_dbs' class=''>Gas left in Cascade(in Kg)</label>
                                        <input readonly type="text" class="input ml-0 col-12" placeholder='Gas left in Cascade(Read only)' id="gas_left_in_dbs" name="gas_left_in_dbs" />
                                    </div>

                                    <button type="submit" id="calct_distance" class="btn btn-primary" value="Calculate Distance">Calculate Distance</button>
                                    <label for='distance' class='d-block'>Distance (in Km)</label>
                                    <input readonly type="text" class="input ml-0 col-12" placeholder='Distance(Km)' id="distance" name="distance">


                                    <br>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">LCV ID</label>
                                        <select class='input ml-0 col-12' name="lcvid" id="lcvid">
                                            <option value="NA" selected>-------------</option>
                                        </select>

                                        <!-- <input type="text" class="form-control" id="lcvid" name="lcvid"> -->
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">LCV Status</label>
                                        <input type="text" class="input ml-0 col-12" id="lcvstatus" name="lcvstatus">

                                    </div>




                                    <input type="hidden" name="id_modal" id="id_modal" class="form-control-sm">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" id="update_detail" class="btn btn-primary" value="Update">Allocate</button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ############################################################################################### -->

                    <div class="card text-center h3">
                        <div class="card-body text-dark"> Tracking LCV Movement
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6 hidden-sm hidden-xs">
                            
                            <table class="table lcv_tracking_table table-striped table-bordered no-wrap mb-0">
                                <thead>
                                    <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                        <th class="header" scope="col"><strong>LCV ID</strong></th>
                                        <th class="header" scope="col"><strong> MGS ID</strong></th>
                                        <th class="header" scope="col"><strong>DBS ID</strong></th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark">
                                    <?php
                                        $lcv_sql = "SELECT * FROM `luag_schedular_trans` WHERE `lcv_status` = 'Scheduled'";
                                        $lcv_result = mysqli_query($conn, $lcv_sql);
                                        while ($lcv = mysqli_fetch_array( $lcv_result, MYSQLI_ASSOC)) {
                                        ?>
                                        <tr class="alert alert-success alert-dismissible  fade show">
                                            <td>
                                                <?php echo $lcv['lcv_id']; ?>
                                            </td>
                                            <td>
                                                <?php echo $lcv['mgs_id']; ?>
                                            </td>
                                            <td>
                                                <?php echo $lcv['dbs_id']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-lg-6 hidden-sm hidden-xs">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3886.6139875875365!2d77.58402861482297!3d13.060225090797745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae1821ca8b1d9f%3A0x8fd257ca32720efd!2sLotus+Value+Developers!5e0!3m2!1sen!2sin!4v1543580574885" width="100%" height="100%" frameborder="0" style="border:0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <?php include('footer.php'); ?>
    <script>
        $(document).ready(function() {

            $(function() {
                $('#update_data').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var dbsid = button.data('title');
                    var mgsid = ''
                    $.ajax({
                        url: "selectlcv.php",
                        method: "POST",
                        data: {
                            dbsid: dbsid
                        },
                        // success: function(data) {
                        //     $('#mgsid').val(data);
                        // }
                        dataType: 'JSON',
                        success: function(response) {
                            var len = response.length;
                            for (var i = 0; i < len; i++) {
                                var mgsId = response[i].mgsId;
                                var Latitude_Longitude = response[i].Latitude_Longitude;
                                $('#mgsid').val(mgsId);
                                $('#latlngDBS').val(Latitude_Longitude);
                            }
                            mgsid = $('#mgsid').val()
                            // console.log('mgsId', mgsid)

                            // Get all LCVs mapped to the selected MGS and fill in the select tab of lcv
                            get_lcv_from_mgs(mgsid)

                            // get mass of gas left in the selected DBS and put the value in 'gas input field' on frontend
                            get_mass_of_gas_left(dbsid, '#gas_left_in_dbs')
                        }
                    });
                    
                    
                    var modal = $(this);
                    modal.find('#dbsid').val(dbsid);
                });
            });

            //get LCV number using MGS ID
            function get_lcv_from_mgs(mgs_id) {
                $.ajax({
                    url:'partials/_fetch_lcv_num.php',
                    type: "POST",
                    data: {
                        mgs: mgs_id,
                    },
                    success: function(response) {
                        // console.log(response)
                        const data = JSON.parse(response)
                        // console.log(data)
                        lcv_list = ''

                        if(data[0].length === 0) { // if no LCV is mapped to selected MGS
                            lcv_list = "<option value = 'NA'>No LCV mapped to selected MGS</option>"
                        } else {
                            lcv_list = "<option value = 'NA'>Select LCV</option>"
                            for (var i in data) {
                                // console.log(data[i][0], data[i][1])
                                lcv_list += `<option value="${data[i][0]}">${data[i][0]} - ${data[i][1]}</option>`
                            }
                        }
                        // fill the LCV dropdown with options
                        $('#lcvid').html(lcv_list)
                    }
                })
            }

            // Get mass of gas left in DBS cascade and fill the value in 'gas input field' on frontend
            function get_mass_of_gas_left(station_id, field_id) {
                $.ajax({
                    url: 'partials/_fetch_dbs_gas_left.php',
                    method: 'POST',
                    data: {
                    station_id: station_id
                    },
                    success: function(response) {
                    // console.log(response)
                        const data = JSON.parse(response)
                        // console.log(data);
                        if(data['data_available'] == true) {
                            $(`${field_id}`).val(data['mass_of_gas'])
                        } else {
                            $(`${field_id}`).val('Data Not Available')
                        }
                    }
                })
            }
            // console.log($('#mgsid').val())
            $('#lcvid').on('change', function() {
                var lcvid = this.value;
                console.log(lcvid)
                $.ajax({
                    // url: "selectlcv.php",
                    url: "partials/_fetch_lcv_status.php",
                    method: "POST",
                    data: {
                        lcvid: lcvid
                    },
                    success: function(data) {
                        console.log(data);
                        $('#lcvstatus').val(data);
                    }
                });
            });

            $(document).on('click', '#calct_distance', function() {
                var dbsid = $('#dbsid').val();
                var mgsid = $('#mgsid').val();
                var latlngDBS = $('#latlngDBS').val();
                $.ajax({
                    url: "calculateDistance.php",
                    method: "POST",
                    data: {
                        calculate: 1,
                        dbsid: dbsid,
                        mgsid: mgsid,
                        latlngDBS: latlngDBS
                    },
                    success: function(data) {
                        $('#distance').val(data);
                    }
                });
            });
            $(document).on('click', '#update_detail', function() {
                var mgsid = $('#mgsid').val();
                var dbsid = $('#dbsid').val();
                var distance = $('#distance').val();
                var lcvid = $('#lcvid').val();
                var lcvstatus = $('#lcvstatus').val();
                $.ajax({
                    url: "update.php",
                    method: "POST",
                    catch: false,
                    data: {
                        update: 1,
                        mgsid: mgsid,
                        dbsid: dbsid,
                        distance: distance,
                        lcvid: lcvid,
                        lcvstatus: lcvstatus

                    },
                    success: function(dataResult) {
                        var dataResult = JSON.parse(dataResult);
                        if (dataResult.statusCode == 1) {
                            $('#update_data').modal().hide();
                            swal("Data Updated!", {
                                icon: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        }
                    }
                });
            });

        });
    </script>

</body>

</html>

<?php
//  } else {
//     echo "<script>show_login_popup()</script>";
// }

?>