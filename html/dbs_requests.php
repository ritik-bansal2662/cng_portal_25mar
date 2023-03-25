<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php 
include '../CNG_API/conn.php';
include('head.php'); 
// $conn = mysqli_connect("localhost", "root", "", "cng_luag");


?>
<style>
    .pending-req, .new-req, .fulfilled-req{
        display: none
    }

    .show-table {
        display: block
    }

    #new-requests, #pending-requests, #fulfilled-requests {
        cursor: pointer
    }

    /* thead th { 
        position: sticky;
        top: 0;
        z-index: 10; 
    } */

    .fixTableHead thead tr { 
        position: sticky; 
        top: 0; 
    } 
    /* tbody {
        z-index: 0;
    } */

</style>


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper  main-content">

        <div class="page-breadcrumb">
            <div class="text-white" style="text-align: center;">
                <h3 class="font-light text-white">DBS Requests</h3>

            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid" style="background-color: #fff999;">

        <div class='gen-info content-active'>
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- basic table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-around">
                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-4 col-xlg-4">
                                        <div class="card card-hover" id='new-requests'>
                                            <div class="p-2 bg-primary text-center">
                                            <h1 class="font-light text-white">
                                            <?php
                                                $result = mysqli_query($conn, "SELECT count(*) New_requests FROM `luag_dbs_request`  WHERE `Status`='New Request'") or die(mysqli_error($conn));
                                                                                $data = mysqli_fetch_assoc($result);
                                                                            ?> 
                                                <?php echo $data['New_requests'];
                                            ?> 
                                            </h1>
                                                <!-- 4</h1> -->
                                                <h6 class="text-white">New Requests</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-4 col-xlg-4">
                                        <div class="card card-hover" id='pending-requests'>
                                            <div class="p-2 bg-danger text-center">
                                            <h1 class="font-light text-white">
                                            <?php
                                                $result = mysqli_query($conn, "SELECT count(*) Pending_Requests FROM `luag_dbs_request` WHERE `Status`='Pending' or `Status`='Previous Pending'") or die(mysqli_error($conn));
                                                                                $data = mysqli_fetch_assoc($result);
                                                                            ?> 
                                                <?php echo $data['Pending_Requests'];?> 
                                            </h1>
                                                <h6 class="text-white">Allocation Pending</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-4 col-xlg-4">
                                        <div class="card card-hover" id='fulfilled-requests'>
                                            <div class="p-2 bg-success text-center">
                                            <h1 class="font-light text-white">
                                            <?php
                                                $result = mysqli_query($conn, "SELECT count(*) Fulfilled FROM `luag_lcv_allocation_to_dbs_request`") or die(mysqli_error($conn));
                                                                                $data = mysqli_fetch_assoc($result);
                                                                            ?> 
                                                <?php echo $data['Fulfilled'];?> 
                                            </h1>
                                                <h6 class="text-white">LCV Allocated to Request</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div id="new-req" class="new-req show-table">
                            <h2>New Requests</h2>
                            <div class="table-responsive fixTableHead">
                                <table class="table table-bordered no-wrap" id="summary">
                                    <thead>
                                        <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">

                                            <th class="header" scope="col"><strong>S.No.</strong></th>
                                            <th class="header" scope="col"><strong>Status</strong></th>
                                            <th class="header" scope="col"><strong>Date</strong></th>
                                            <th class="header" scope="col"><strong>Time</strong></th>
                                            <th class="header" scope="col"><strong>DBS ID</strong></th>
                                            <th class="header" scope="col"><strong>MGS ID</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $new_req_query = mysqli_query($conn, "SELECT * FROM `luag_dbs_request`
                                            WHERE `STATUS`='New Request'");
                                        $i = 1;
                                        while ($row = $new_req_query->fetch_assoc()) {
                                            $status = $row["STATUS"];
                                            $dt = new DateTime($row['create_date']);
                                            $date = $dt->format('d M Y');
                                            // $d = date('d M Y', strtotime($date));
                                            $time = $dt->format('H:i:s');
                                            $dbs = $row["DBS"];
                                            $mgs = $row["MGS"];
                                            echo "<tr class='alert alert-success alert-dismissible fade show'>
                                                <td> $i </td>
                                                <td> $status </td>
                                                <td> $date </td>
                                                <td> $time </td>
                                                <td> $dbs </td>
                                                <td> - </td>
                                            </tr>";
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- <ul class="pagination float-right">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                </ul> -->
                            </div>
                        </div>

                        <div id="pending-req" class="pending-req">
                            <h2>Pending Requests</h2>
                            <div class="table-responsive fixTableHead">
                                <table class="table table-bordered no-wrap" id="summary">
                                    <thead>
                                        <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
    
                                            <th class="header" scope="col"><strong>S.No.</strong></th>
                                            <th class="header" scope="col"><strong>Status</strong></th>
                                            <th class="header" scope="col"><strong>Date</strong></th>
                                            <th class="header" scope="col"><strong>Time</strong></th>
                                            <th class="header" scope="col"><strong>DBS ID</strong></th>
                                            <th class="header" scope="col"><strong>MGS ID</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
    
                                        $pending_req_query = mysqli_query($conn, "SELECT * FROM `luag_dbs_request`
                                            WHERE `STATUS`='Pending' or `Status`='Previous Pending'");
                                        $j = 1;
                                        while ($req_row = $pending_req_query->fetch_assoc()) {
                                            $status = $req_row["STATUS"];
                                            $dt = new DateTime($req_row['create_date']);
                                            $date = $dt->format('d M Y');
                                            // $d = date('d F Y', strtotime($date));
                                            $time = $dt->format('H:i:s');
                                            $pen_dbs = $req_row["DBS"];
                                            $pen_mgs = $req_row["MGS"];
                                            echo "<tr class='alert alert-danger alert-dismissible fade show'>
                                                    <td> $j </td>
                                                    <td> $status </td>
                                                    <td> $date </td>
                                                    <td> $time </td>
                                                    <td> $pen_dbs </td>
                                                    <td> $pen_mgs </td>
                                                </tr>";
                                            $j++;
                                        }
                                        ?>
                                    </tbody>
                                
                                    <!-- <tfoot>
                                                <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                                    <th class="header" scope="col"><strong>Slno</strong></th>
                                                    <th class="header" scope="col"><strong>Date</strong></th>
                                                    <th class="header" scope="col"><strong>Time</strong></th>
    
                                                    <th class="header" scope="col"><strong>Station ID</strong></th>
    
                                                    <th class="header" scope="col"><strong>Gas in Cascade</strong></th>
                                                </tr>
                                            </tfoot> -->
                                </table>
                            </div>
                        </div>

                        <div id="fulfilled-req" class="fulfilled-req">
                            <h2>Fulfilled Requests</h2>
                            <div class="table-responsive fixTableHead">
                                <table class="table table-striped table-bordered no-wrap mb-0" id="summary">
                                    <thead>
                                        <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                            <th class="header" scope="col"><strong>S.No.</strong></th>
                                            <th class="header" scope="col"><strong>Date</strong></th>
                                            <th class="header" scope="col"><strong>Time</strong></th>
                                            <th class="header" scope="col"><strong>DBS ID</strong></th>
                                            <th class="header" scope="col"><strong>MGS ID</strong></th>
                                            <th class="header" scope="col"><strong>LCV Number</strong></th>
                                            <th class="header" scope="col"><strong>LCV Stage</strong></th>
                                            <th class="header" scope="col"><strong>Route Duration(Hrs)</strong></th>
                                            <th class="header" scope="col"><strong>Route Distance(Km)</strong></th>
                                            <th class="header" scope="col"><strong>Route Description</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $ful_req_query = mysqli_query($conn, "SELECT *
                                            FROM (((luag_lcv_allocation_to_dbs_request a
                                            INNER JOIN luag_dbs_request b ON b.Request_id = a.Request_id)
                                            INNER JOIN luag_dbs_to_mgs_routes c ON c.Route_id = b.Route_id)
                                            INNER JOIN notification d ON d.Notification_Id = a.Notification_Id)
                                            ORDER BY a.Allocation_date"
                                        );
                                        $k = 1;
                                        while ($row_req = $ful_req_query->fetch_assoc()) {
                                            $dt = new DateTime($row_req['Allocation_date']);
                                            $date = $dt->format('d M Y');
                                            // $d = date('d M Y', strtotime($date));
                                            $time = $dt->format('H:i:s');
                                            $f_dbs = $row_req["DBS"];
                                            $f_mgs =  $row_req["MGS"];
                                            $f_lcv = $row_req["LCV_Num"];
                                            $duration = $row_req["Route_Duration"]; 
                                            $distance = $row_req["Route_distance"];
                                            $desc = $row_req["Route_description"];
                                            $stage = $row_req["flag"];

                                        echo "<tr class='alert alert-success alert-dismissible fade show'>
                                                <td> $k</td>
                                                <td> $date </td>
                                                <td> $time </td>
                                                <td> $f_dbs </td>
                                                <td> $f_mgs </td>
                                                <td> $f_lcv </td>
                                                <td> $stage </td>
                                                <td> $duration </td>
                                                <td> $distance </td>
                                                <td> $desc </td>
                                            </tr>";
                                            $k++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- <ul class="pagination float-right">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                </ul> -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

     <!-- <script type="text/javascript" src=" https://code.jquery.com/jquery-3.5.1.js"></script> -->
     <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <!--    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    -->

        <?php 
            include('footer.php'); 
        ?>

        <script>
            $('#new-requests').click(function(){
                // console.log('new');
                $('#new-req').addClass('show-table')
                $('#pending-req').removeClass('show-table')
                $('#fulfilled-req').removeClass('show-table')
            })
            $('#pending-requests').click(function(){
                // console.log('pending');
                $('#pending-req').addClass('show-table')
                $('#new-req').removeClass('show-table')
                $('#fulfilled-req').removeClass('show-table')
            })
            $('#fulfilled-requests').click(function(){
                // console.log('fulfilled');
                $('#fulfilled-req').addClass('show-table')
                $('#pending-req').removeClass('show-table')
                $('#new-req').removeClass('show-table')
            })
        </script>
</body>

</html>