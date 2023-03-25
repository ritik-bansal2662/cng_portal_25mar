<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
} else if($_SESSION['mgs_id'] == 'NA' && $_SESSION['admin'] != true) {
    header("location: index.php");
    exit();
}

include '../CNG_API/conn.php';

$mgs = $_SESSION['mgs_id'];

$allocated_lcv_query = "SELECT * from luag_lcv_allocation_to_dbs_request where MGS = '$mgs' and Status = 'Scheduled'";

if($_SESSION['admin'] == true) {
    $allocated_lcv_query = "SELECT * from luag_lcv_allocation_to_dbs_request where Status = 'Scheduled'";
}

$allocated_lcv_result = mysqli_query($conn, $allocated_lcv_query);

$data = array();

while($allocated_lcv_row = $allocated_lcv_result->fetch_assoc()){
    $data[$allocated_lcv_row['LCV_Num']] = $allocated_lcv_row;
}



$all_lcv_query = "WITH ranked_lcv AS (
    SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
    FROM notification AS m
  ) SELECT * FROM ranked_lcv WHERE rn = 1 and Notification_MGS = '$mgs' and flag in (1,2)";

if($_SESSION['admin'] == true) {
    $all_lcv_query = "WITH ranked_lcv AS (
        SELECT m.*, ROW_NUMBER() OVER (PARTITION BY Notification_LCV ORDER BY Notification_Id DESC) AS rn
        FROM notification AS m
      ) SELECT * FROM ranked_lcv WHERE rn = 1 and flag in (1,2)";
}



$all_lcv_result = mysqli_query($conn, $all_lcv_query);

while($all_lcv_row = $all_lcv_result->fetch_assoc()){
    $temp = array(
        'Notification_Id' => $all_lcv_row['Notification_Id'],
        'Request_id' => 'NA',
        'MGS' => $all_lcv_row['Notification_MGS'],
        'DBS' => $all_lcv_row['Notification_DBS'],
        'Stage' => $all_lcv_row['flag'],
        'Date' => $all_lcv_row['create_date'],
        'Status' => 'Not allocated'
    );
    if(array_key_exists($all_lcv_row['Notification_LCV'], $data)) {
        $data[$all_lcv_row['Notification_LCV']]['Stage'] = $all_lcv_row['flag'];
        $data[$all_lcv_row['Notification_LCV']]['Date'] = $data[$all_lcv_row['Notification_LCV']]['Allocation_date'];
    } else {
        $data[$all_lcv_row['Notification_LCV']] = $temp;
    }
}

$select_sql = "SELECT * from luag_station_master where Station_type = 'Daughter Booster Station'";
$result = mysqli_query($conn, $select_sql);
$num_rows = mysqli_num_rows($result);
$all_dbs = "<option value='NA'>Select DBS id</option>";
while($row = $result-> fetch_assoc()) {
    $all_dbs .= "<option data-coordinates='". $row['Latitude_Longitude'] ."' value='".$row['Station_Id']."'>".$row['Station_Id']."</option>";
}

?>

<!DOCTYPE html>
<html lang="en">
<?php 
include '../CNG_API/conn.php';
include('head.php'); 


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
                <h3 class="font-light text-white">Manual Override Allocation of LCV</h3>
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
                        
                        <hr />

                        <div id="new-req" class="new-req show-table">
                            <div class="table-responsive fixTableHead">
                                <table class="table table-striped table-bordered no-wrap" id="summary">
                                    <thead>
                                        <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">

                                            <th class="header" scope="col"><strong>S.No.</strong></th>
                                            <th class="header" scope="col"><strong>LCV Number</strong></th>
                                            <th class="header" scope="col"><strong>Date</strong></th>
                                            <th class="header" scope="col"><strong>Time</strong></th>
                                            <th class="header" scope="col"><strong>MGS ID</strong></th>
                                            <th class="header" scope="col"><strong>Allocated DBS ID</strong></th>
                                            <th class="header" scope="col"><strong>Stage</strong></th>
                                            <th class="header" scope="col"><strong>Status</strong></th>
                                            <th class="header" scope="col"><strong>Manual DBS</strong></th>
                                            <th class="header" scope="col"><strong>Approve</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $i = 1;
                                        foreach($data as $lcv => $lcv_details){
                                            $dt = new DateTime($lcv_details['Date']);
                                            $date = $dt->format('d M Y');
                                            $time = $dt->format('H:i:s');
                                            $mgs_id = $lcv_details["MGS"];
                                            $dbs = $lcv_details["DBS"];
                                            $dbs_data = $dbs;
                                            if($dbs == '' || $dbs == null) {
                                                $dbs = '';
                                                $dbs_data = "Not Scheduled";
                                            }
                                            $stage = $lcv_details['Stage'];
                                            $status = $lcv_details['Status'];
                                            echo "<tr class='alert alert-success alert-dismissible fade show'>
                                                <td> $i </td>
                                                <td> $lcv </td>
                                                <td> $date </td>
                                                <td> $time </td>
                                                <td> $mgs_id </td>
                                                <td> $dbs_data </td>
                                                <td> $stage </td>
                                                <td> $status </td>
                                                <td class='all-dbs'>
                                                    <select class='select-dbs input col-20'> 
                                                        $all_dbs 
                                                    </select>
                                                </td>
                                                <td> <input 
                                                    type='button' 
                                                    class='btn btn-primary' 
                                                    value='Approve' 
                                                    data-lcv='$lcv' 
                                                    data-mgs='$mgs_id'
                                                    data-allocated_dbs='$dbs'
                                                    onClick='approve(event)' 
                                                > </td>
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
    <Script type="text/javascript">
        <?php include '../dist/js/manual_scheduling.js' ?> 
    </Script>

        <?php 
            include('footer.php'); 
        ?>
</body>

</html>