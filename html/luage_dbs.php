<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
    <title>Reconciliation Data at Daughter Booster Station</title>
    <?php include('head.php'); ?>
    <style>
        .container {
            margin: 50px 50px;
        }

        thead {
            color: white;

        }

        tbody {
            color: black;

        }

        tfoot {
            color: red;
        }

        table,
        th,
        td {
            border: 1px solid white;
        }
        table th {
            background-color: gray;
        }
        th, td {
            text-align: center
        }
        .table thead th {
            vertical-align: middle;
        }

        .ddtf-processed th.option-item>select {
            display: none;
        }

        .ddtf-processed th.option-item>div {
            display: block !important;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h2 style="color:white;">Reconciliation at Daughter Booster Station</h2>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #fff999;">
            <!-- <section class="header">
                <h2 align="center">Reconciliation at Daughter Booster Station</h2>

            </section> -->

            <div class="w3-container ">
                <div id='download_excel_div'><button id='download_excel' class='btn btn-primary'>Download Report</button></div>
                <div class="w3-responsive">
                    <!-- <table class="w3-table-all ">
                        <thead style="background-color: #02603E;color:white">
                            <tr>
                                <th bgcolor=" #02603E" class="header" scope="col" colspan="40"><strong> &nbsp</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col" colspan="40"><strong> Before emptying LCV Cascade &nbsp</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col" colspan="4"><strong> After Emptying the LCV Cascade</strong></th>
                                <th bgcolor=" #02603E"><strong>Gas Received at DBS</strong></th>
                            </tr>
                        </thead>
                    </table> -->
                    <!-- <p>*BELC:Before emptying LCV Cascade</p>
                    <p>*AELC:After Emptying the LCV Cascade</p> -->
                    <!-- <table class="w3-table-all w3-small" style="width: 100%;">
                        <thead>
                            <tr>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 43.5%;" colspan="4"><strong></strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 24.5%;" colspan="4"><strong> Before emptying LCV Cascade &nbsp</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 24.5%;" colspan="4"><strong> After Emptying the LCV Cascade</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" colspan="4"><strong>Gas Received at DBS</strong></th>
                            </tr>
                        </thead>
                    </table> -->
                    <div class="table_div">
                    <!-- w3-table-all w3-small -->
                        <table id="mytable" class="table table-striped table-bordered fade show">
                            <thead>
                                <!-- <tr>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong> </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong> Before emptying LCV Cascade &nbsp</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong> After Emptying the LCV Cascade</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" colspan="4"><strong>Gas Received at DBS</strong></th>
                                </tr> -->

                                <tr>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Month</strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Time</strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong> LCV Number </strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong> Mother Gas Station </strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong> Daughter Booster Station </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Pressure gauge @LCV Before Emptying (Bar) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Temperature gauge @LCV Before Emptying (Degree C) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Volume of LCV Before Emptying (WL) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Mass of Gas Before Emptying in lCV  (Kg) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Pressure gauge @LCV After Emptying (bar) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Temperature gauge @LCV After Emptying (Degree C) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Volume of LCV After Emptying (WL) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Mass of Gas After Emptying in lCV (Kg) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Total Gas Received at DBS (Kg) </strong></th>

                                </tr>


                            </thead>
                            <tbody style="background-color: #E9C006; ">
                                <?php
                                $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");
                                function decimalHours($time)
                                {
                                    $hms = explode(":", $time);
                                    return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
                                }
                                $stmt = $db->prepare("SELECT *,MONTHNAME(date_reading) month,time(create_date) time,Cascade_Capacity from luag_transaction_master_dbs_station,reg_lcv where 
                                lcv_id=Lcv_Num and lcv_from_mgs_to_dbs='6'
                                ORDER BY sl_no desc");
                                $stmt->execute();
                                while ($row = $stmt->fetch()) {
                                ?>
                                    <tr>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["month"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["date_reading"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["time"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["lcv_id"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["station_id"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["dbs_station_id"]; ?>
                                        </td>
                                        <td bgcolor="#808080">
                                            <?php echo round($row["before_empty_at_db_value_pressure_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["before_empty_at_db_value_temperature_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["Cascade_Capacity"]; ?>
                                        </td>
                                        <td bgcolor="#F8A000">
                                            <?php echo round($row["before_empty_at_db_mass_cng"], 2); ?>
                                        </td>

                                        <td bgcolor="#808080">
                                            <?php echo round($row["after_empty_at_dbs_value_pressure_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["after_empty_at_dbs_value_temperature_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["Cascade_Capacity"]; ?>
                                        </td>
                                        <td bgcolor="#F8A000">
                                            <?php echo round($row["after_empty_at_dbs_mass_cng"], 2); ?>
                                        </td>
                                        <td bgcolor="#984352" style="color: white;">
                                            <?php echo round($row["before_empty_at_db_mass_cng"] - $row["after_empty_at_dbs_mass_cng"], 2); ?>
                                        </td>
                                    </tr>
                                <?php
                                    $decimalHours = 0;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <button id='download_excel'>download Report</button> -->
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="ddtf.js"></script>
    <script src="../dist/js/table2excel.js"></script>

    <script>
        $('#download_excel').click(function() {
            var table2excel = new Table2Excel();
            table2excel.export($("#mytable"));
        })
    </script>
    
    <script>
        $("#mytable").ddTableFilter();
    </script>
    <?php include('footer.php'); ?>


</body>

</html>