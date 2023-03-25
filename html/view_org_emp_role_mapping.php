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
    tbody#table_body {
        background-color: #f4f8fb;
    }
</style>


<body>
    <?php include('header.php'); ?>
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class=" align-self-center">
                <h3 style="color:white;">View Org/Emp Role Mapping</h3>
            </div>
        </div>

        

        <div class="container-fluid" style="background-color: #f9ffd0;">
            <div class='gen-info d-block'>
                <div class="table-responsive fixTableHead">
                    <table class="table table-striped table-bordered no-wrap mb-0">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col"><strong> S.No</strong></th>
                                <th class="header" scope="col"><strong>Employee ID</strong></th>
                                <th class="header" scope="col"><strong>Employee Name</strong></th>
                                <th class="header" scope="col"><strong>Role</strong></th>
                                <th class="header" scope="col"><strong>Organization</strong></th>
                                <th class="header" scope="col"><strong>MGS</strong></th>
                                <th class="header" scope="col"><strong>DBS</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-dark" id='table_body'>
                            <?php
                                
                                include '../CNG_API/conn.php';

                                $select_sql = "SELECT * from luag_role_mapping";
                                $emp_select_query = "SELECT a.*, b.* from luag_employee_registration a, luag_role_mapping b where b.Employee_Id = a.Emp_num";
                                $emp_result = mysqli_query($conn, $emp_select_query);
                                // print_r($result);
                                // echo "<br><br><br>";
                                $emp_num_rows = mysqli_num_rows($emp_result);

                            $i=1;
                            while($emp_row = mysqli_fetch_array($emp_result, MYSQLI_ASSOC)) {
                                // print_r($emp_row);
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $emp_row['Emp_id']; ?></td>
                                    <td><?php echo $emp_row['Emp_First_Name'] . " " .$emp_row['Emp_Middle_Name'] . " " . $emp_row['Emp_Last_Name']; ?></td>
                                    <td><?php echo $emp_row['User_Role']; ?></td>
                                    <td><?php echo $emp_row['Orgnization_Id']; ?></td>
                                    <td>
                                        <?php 
                                            if($emp_row['note_approver_mgs'] == 'NA') {
                                                echo "----";
                                            } else{
                                                echo $emp_row['note_approver_mgs'];
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if($emp_row['note_approver_dbs'] == 'NA') {
                                                echo "----";
                                            } else{
                                                echo $emp_row['note_approver_dbs'];
                                            }
                                        ?>
                                    </td>
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