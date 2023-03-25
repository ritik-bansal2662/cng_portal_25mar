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


?>


<!DOCTYPE html>
<html lang="en">

<?php include('head.php'); ?>
<style rel='stylesheet'>
    <?php include '../dist/css/edit-station.css'; ?>
</style>


<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white;">View LCV Details</h3>
            </div>
        </div>

        

        <div class="container-fluid" style="background-color: #f9ffd0;">
            <div class='gen-info d-block'>
                <div class="table-responsive fixTableHead">
                    <table class="table table-striped table-bordered no-wrap mb-0">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col"><strong>S.No</strong></th>
                                <th class="header" scope="col"><strong>LCV Number</strong></th>
                                <th class="header" scope="col"><strong>Vendor Name</strong></th>
                                <th class="header" scope="col"><strong>Type</strong></th>
                                <th class="header" scope="col"><strong>Chassis Number</strong></th>
                                <th class="header" scope="col"><strong>Engine number</strong></th>
                                <th class="header" scope="col"><strong>Cascade Capacity</strong></th>
                                <th class="header" scope="col"><strong>Fuel Type</strong></th>
                                <th class="header" scope="col"><strong>Status</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-dark" id='table_body'>
                            <?php
                                
                                include '../CNG_API/conn.php';

                                $lcv_select_sql = "SELECT * from reg_lcv";
                                $lcv_select_sql = mysqli_query($conn, $lcv_select_sql);
                                // print_r($result);
                                // echo "<br><br><br>";
                                $lcv_num_rows = mysqli_num_rows($lcv_select_sql);

                            $i=1;
                            while($lcv_row = mysqli_fetch_array($lcv_select_sql, MYSQLI_ASSOC)) {
                                // print_r($lcv_row);
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $lcv_row['Lcv_Num']; ?></td>
                                    <td><?php echo $lcv_row['Lcv_Registered_To']; ?></td>
                                    <td><?php echo $lcv_row['Vechicle_Type']; ?></td>
                                    <td><?php echo $lcv_row['Chassis_Num']; ?></td>
                                    <td><?php echo $lcv_row['Engine_Num']; ?></td>
                                    <td><?php echo $lcv_row['Cascade_Capacity']; ?></td>
                                    <td><?php echo $lcv_row['Fuel_Type']; ?></td>
                                    <td><?php echo $lcv_row['lcv_status']; ?></td>
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
</body>
</html>