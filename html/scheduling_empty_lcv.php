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
    /* .pending-req, .new-req, .fulfilled-req{
        display: none
    }

    .show-table {
        display: block
    }

    #new-requests, #pending-requests, #fulfilled-requests {
        cursor: pointer
    } */

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
                <h3 class="font-light text-white">Scheduling Empty LCV</h3>
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

            <div id="new-req" class="new-req show-table">
                <h2>Empty LCV Schedule</h2>

                <hr />

                <div class="table-responsive fixTableHead">
                    <table class="table table-bordered no-wrap" id="summary">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">

                                <th class="header" scope="col"><strong>S.No.</strong></th>
                                <th class="header" scope="col"><strong>LCV Number</strong></th>
                                <th class="header" scope="col"><strong>Date</strong></th>
                                <th class="header" scope="col"><strong>Time</strong></th>
                                <th class="header" scope="col"><strong>DBS ID</strong></th>
                                <th class="header" scope="col"><strong>MGS ID</strong></th>
                                <th class="header" scope="col"><strong>Status</strong></th>
                                <th class="header" scope="col"><strong>Route Duration(Hrs)</strong></th>
                                <th class="header" scope="col"><strong>Route Distance(Km)</strong></th>
                                <th class="header" scope="col"><strong>Route Description</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $scheduled_lcv = mysqli_query($conn, "SELECT *
                                FROM (luag_empty_lcv_scheduling a
                                INNER JOIN luag_dbs_to_mgs_routes b ON b.Route_id = a.Route_id)
                                where a.status = 'Scheduled' ORDER BY a.create_date desc");
                            $count_rows = mysqli_num_rows($scheduled_lcv);

                            if($count_rows == 0) {
                                echo "<tr class='alert alert-success alert-dismissible fade show'>
                                <td> 1 </td>
                                <td colspan='9'> No Scheduling Record found </td>
                                </tr>";
                            }
                            
                            $i = 1;
                            while ($row = $scheduled_lcv->fetch_assoc()) {
                                $dt = new DateTime($row['create_date']);
                                $date = $dt->format('d M Y');
                                // $d = date('d M Y', strtotime($date));
                                $time = $dt->format('H:i:s');
                                $dbs = $row["DBS"];
                                $mgs = $row["MGS"];
                                $lcv_num = $row["LCV_num"];
                                $route_duration = $row["Duration"]; 
                                $route_distance = $row["Distance"];
                                $route_description = $row["Route_description"];
                                $status = $row["status"];
                                echo "<tr class='alert alert-success alert-dismissible fade show'>
                                    <td> $i </td>
                                    <td> $lcv_num </td>
                                    <td> $date </td>
                                    <td> $time </td>
                                    <td> $dbs </td>
                                    <td> $mgs </td>
                                    <td> $status </td>
                                    <td> $route_duration </td>
                                    <td> $route_distance </td>
                                    <td> $route_description </td>
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
</body>

</html>