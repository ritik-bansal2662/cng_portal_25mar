<!DOCTYPE html>
<html lang="en">
<?php 

include('head.php'); 
$db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "ritikAbes$2662");

$approved = $db->prepare("SELECT * 
    FROM notification 
    WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`) 
    and  `status` = 'Approved'
    order by Notification_Id desc");
$approved->execute();

$pending = $db->prepare("SELECT * 
    FROM notification 
    WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`) 
    and  `status` = 'Pending'
    order by Notification_Id desc");
$pending->execute();

$stmt = $db->prepare("SELECT * 
    FROM notification 
    WHERE `Notification_Id` IN (SELECT MAX(`Notification_Id`) FROM notification GROUP BY `Notification_DBS`)
    order by Notification_Id desc");
$stmt->execute();


?>


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">

        <div class="page-breadcrumb">
            <div class="text-white" style="text-align: center;">
                <h2 style="color:white;">Notification</h2>

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
                                        <div class="p-2 bg-primary text-center" id='total'>
                                            <h1 class="font-light text-white"><?php echo $stmt->rowCount(); ?></h1>
                                            <h6 class="text-white">Total Notifications</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-4 col-xlg-4">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-danger text-center" id='pending'>
                                            <h1 class="font-light text-white"><?php echo $pending->rowCount(); ?></h1>
                                            <h6 class="text-white">Approval Pending</h6>
                                        </div>
                                    </div>
                                </div>

                                <!-- Column -->
                                <div class="col-md-6 col-lg-4 col-xlg-4">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-success text-center" id='approved'>
                                            <h1 class="font-light text-white"><?php echo $approved->rowCount(); ?></h1>
                                            <h6 class="text-white">Approved</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column -->
                    </div>
                    <div class="table-responsive fixTableHead">
                        <table id="zero_config" class="table table-striped table-bordered no-wrap" id="summary">
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
                            <tbody id='tbody'>
                                
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

    <script>
        <?php include '../dist/js/notification.js' ?> 
    </script>

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