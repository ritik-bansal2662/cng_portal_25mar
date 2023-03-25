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


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper  main-content">

        <div class="page-breadcrumb">
            <div class="text-white" style="text-align: center;">
                <h3 class="font-light text-white">Notification</h3>

            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- basic table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <!-- Column -->
                                <div class="col-md-6 col-lg-4 col-xlg-4">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-primary text-center">
                                        <h1 class="font-light text-white">
<?php
                                        $result = mysqli_query($conn, "SELECT count(*) total FROM `notification`") or die(mysqli_error($conn));
																			$data = mysqli_fetch_assoc($result);
																		?> <?php echo $data['total'];
																																							 ?> 
</h1>
                                            <!-- 4</h1> -->
                                            <h6 class="text-white">Total Notifications</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-4 col-xlg-4">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-danger text-center">
                                        <h1 class="font-light text-white">
<?php
                                        $result = mysqli_query($conn, "SELECT count(*) total FROM `notification` WHERE `status`='Pending'") or die(mysqli_error($conn));
																			$data = mysqli_fetch_assoc($result);
																		?> <?php echo $data['total'];
																																							 ?> 
</h1>
                                            <h6 class="text-white">Approval Pending</h6>
                                        </div>
                                    </div>
                                </div>

                                <!-- Column -->
                                <div class="col-md-6 col-lg-4 col-xlg-4">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-success text-center">
                                        <h1 class="font-light text-white">
<?php
                                        $result = mysqli_query($conn, "SELECT count(*) total FROM `notification` WHERE `status`='Approved'") or die(mysqli_error($conn));
																			$data = mysqli_fetch_assoc($result);
																		?> <?php echo $data['total'];
																																							 ?> 
</h1>
                                            <h6 class="text-white">Approved</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column -->
                    </div>
                    <div class="table-responsive fixTableHead">
                        <table id="zero_config" class="table table-bordered no-wrap" id="summary">
                            <thead>
                                <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">

                                    <th class="header" scope="col"><strong>Slno</strong></th>
                                    <th class="header" scope="col"><strong>Status</strong></th>
                                    <th class="header" scope="col"><strong>Date</strong></th>
                                    <th class="header" scope="col"><strong>Time</strong></th>
                                    <th class="header" scope="col"><strong>LCV ID</strong></th>
                                    <th class="header" scope="col"><strong>MGS ID</strong></th>
                                    <th class="header" scope="col"><strong>DBS ID</strong></th>
                                    <th class="header" scope="col"><strong>Message</strong></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");

                                $stmt = $db->prepare("SELECT * 
                                        FROM notification 
                                        WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`)
                                        order by Notification_Id desc");
                                $stmt->execute();
                                $i = 1;
                                while ($row = $stmt->fetch()) {
                                    $status = $row["status"];
                                    if ($status == 'Pending') {
                                ?>
                                        <tr class="alert alert-danger alert-dismissible fade show">
                                            <td>
                                                <?php echo $i; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["status"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["Notification_Date"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["Notification_Time"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["Notification_LCV"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["Notification_MGS"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["Notification_DBS"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["Notification_Message"]; ?>
                                            </td>


                                        </tr>
                                    <?php } else                                           
                                           if ($status == 'Approved') {
                                        // echo "<tr style='background-color:powderblue;'>"; ?>
                                        <!-- <tr class="alert alert-success alert-dismissible "> -->
                                        <tr class="alert alert-success alert-dismissible fade show">
                                            <td>
                                                <?php echo $i; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["status"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["Notification_Date"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["Notification_Time"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["Notification_LCV"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["Notification_MGS"]; ?>
                                            </td>

                                            <td>
                                                <?php echo $row["Notification_DBS"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $row["Notification_Message"]; ?>
                                            </td>



                                        </tr>
                                <?php   }
                                    $i++;
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

    <!-- <script type="text/javascript" src=" https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

        <?php include('footer.php'); ?>

        <script>
            $(document).ready(function() {
                $(document).ready(function() {
                    $('#summary').DataTable({
                        dom: 'Blfrtip',
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ]
                    });
                });
            });
        </script> -->
</body>

</html>