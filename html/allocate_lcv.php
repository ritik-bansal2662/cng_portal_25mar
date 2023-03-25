<?php

error_reporting(0);

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
    .station_id {
        border: 2px solid black;
        /* margin: 20px; */
        padding: 10px;
        border-radius: 10px;
        display: flex; 
        justify-content: center;
        /* align-items: center; */
        flex-direction: column;
    }
    .station_id .checkbox_group {
        margin-left: 10%;
    }
    .station_id label {
        margin: 5px;
        font-size: 20px;
        color: #000;
    }
    .page-wrapper {
        /* position: relative; */
    }
    .page-breadcrumb {
        /* position: sticky;
        top: 0; */
    }
    .page-heading {
        /* height: 100px;*/
        width: 100%;
        background: #02603e;
        /* align: center; */
        position: fixed;
        top: 80px;
        z-index:1;
        padding: auto;
    }
</style>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"> -->


<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb ">
        <!-- style="text-align: center;" -->
                <div class="text-white">
                    <h3 style="color:white;">Allocate LCV to MGS</h3>
                </div>
        </div>
        <!-- <div class="page-heading">
            <h2>LCV</h2>
        </div> -->

        <div class="container-fluid" style="background-color: #fff999;">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->

            <div class='gen-info d-block'>
                <div class=''>
                    <form id='lcv_allocation_form'>
                        <div class='col-12 inp-group'>
                            <!-- <div class='col-lg-5 m-auto col-12 '>
                                <label for='organization_id'>Select Organization</label>
                                <select required name='organization_id' class='input col-12' id='organization_id'>
                                </select>
                            </div> -->
                            <div class='col-lg-8 m-auto col-12 '>
                                <label for='lcv'>Select LCV</label>
                                <select required name='lcv' class='input col-12' id='lcv'>
                                    <?php 
                                        include '../../CNG_API/conn.php';

                                        $lcv_select_sql = "select * from reg_lcv";
                                        $lcv_result = mysqli_query($conn, $lcv_select_sql);
                                        $lcv_num_rows = mysqli_num_rows($lcv_result);
                                        $output='';
                                        if($lcv_num_rows > 0) {
                                            $output .= "<option value='NA'>Select Lcv Number</option>";
                                            while($lcv_row = $lcv_result-> fetch_assoc()) {
                                                $output .= "<option value='".$lcv_row['Lcv_Num']."'>".$lcv_row['Lcv_Num'] . " - " . $lcv_row['Lcv_Registered_To'] . "</option>";
                                            }
                                        } else {
                                            $output = "<option value='NA'>No LCV found</option>";
                                        }

                                        echo $output;

                                    ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class='col-12 inp-group'>
                            <div class='col-lg-8 col-12'>
                                <h2>Select MGS</h2>
                                    <div class='station_id' id='station_id'>
                                        <?php
                                            include "../CNG_API/conn.php";
                                            $select_sql_mgs = "select * from luag_station_master where Station_type = 'Mother Gas Station'";
                                            $result_mgs = mysqli_query($conn, $select_sql_mgs);
                                            $num_rows_mgs = mysqli_num_rows($result_mgs);
                                            $output_mgs='';
                                            if($num_rows_mgs>0) {
                                                $output .= "<option value='NA'>Select LCV id</option>";
                                                while($row_mgs = $result_mgs-> fetch_assoc()) {
                                                    $output_mgs .= '<div class="checkbox_group"><input type="checkbox" name="lcv_mgs" class="mgs_checkbox" value="'. $row_mgs['Station_Id'] .'"><label for="vehicle1">'. $row_mgs['Station_Id'] . " - " . $row_mgs['Station_Name'] .'</label></div>';
                                                }
                                            }
                                            //  else {
                                            //     $output = "<option value='NA'>No DBS found</option>";
                                            // }
                                            echo $output_mgs;
                                        ?>
                                    </div>

                            </div>
                            <!-- <div class='col-lg-5 col-12 '>
                                <h2>Select DBS</h2>
                                <div class='station_id' id='dbs_id'> -->
                                    <?php
                                        // $select_sql_dbs = "select * from luag_station_master where Station_type = 'Daughter Booster Station'";
                                        // $result_dbs = mysqli_query($conn, $select_sql_dbs);
                                        // $num_rows_dbs = mysqli_num_rows($result);
                                        // // print_r($result_dbs);
                                        // // echo $num_rows_dbs;
                                        // $output_dbs='';
                                        // if($num_rows_dbs>0) {
                                        //     // $output .= "<option value='NA'>Select DBS id</option>";
                                        //     // echo 'b';
                                        //     while($row_dbs = $result_dbs-> fetch_assoc()) {
                                        //         // $output .= "<option value='".$row['Station_Id']."'>".$row['Station_Id']."</option>";
                                        //         $output_dbs .= '<div class="checkbox_group"><input type="checkbox" name="lcv_dbs" class="dbs_checkbox" value="'. $row_dbs['Station_Id'] .'"><label for="vehicle1">'. $row_dbs['Station_Id'] .'</label></div>';
                                        //     }
                                        // } 
                                        // echo $output_dbs;

                                    ?>
                                <!-- </div>
                            </div> -->
                        </div>
                        <!-- <button type='button' id='' class='btn btn-primary'>Submit</button> -->
                        <div class='form-buttons col-12'>
                            <!-- <input type='button' class='btn btn-warning cancel-btn' value='Reset' /> -->
                            <button type='button' id='allocate_btn' class='btn btn-primary submit-btn'> ALLOCATE </button>
                        </div>
                    </form>

                    <br><hr><br>

                    <table class="table table-striped table-bordered no-wrap mb-0">
                        <thead>
                            <tr class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show">
                                <th class="header" scope="col"><strong>S.No.</strong></th>
                                <th class="header" scope="col"><strong>LCV ID</strong></th>
                                <th class="header" scope="col"><strong>Vendor Name</strong></th>
                                <th class="header" scope="col"><strong> MGS ID</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-dark" id='table_body'>
                                <!-- <tr class="alert alert-success alert-dismissible  fade show">
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr> -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>












    </div>



    <Script type="text/javascript">
        <?php require '../dist/js/allocate_lcv.js'; ?>
    </Script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <?php include('footer.php'); ?>

</body>
</html>