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
    <title>Reconciliation at Mother Gas Station</title>
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
        .table thead th {
            vertical-align: middle;
        }

        th, td{
            /* padding: 1px; */
            text-align: center;
            /* vertical-align: middle; */
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
                <h2 style="color:white;">Reconciliation at Mother Gas Station</h2>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #fff999;">

            <div class="w3-container ">
                <!-- <h2 align="center"></h2> -->
                <div id='download_excel_div'><button id='download_excel' class='btn btn-primary'>Download Report</button></div>
                <div class="w3-responsive">
                    <!-- <p>*BRC: Before Refilling of Cascade,*MFMR: Mass Flow Meter Readings,*AFC: After Refilling of Cascade
                    </p> -->
                    <!-- <table class="w3-table-all w3-small" style="width: 110%;">
                        <thead>
                            <tr>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 39.5%;" colspan="4"><strong></strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 15%;" colspan="4"><strong>Before Refilling of Cascade</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 22.5%;" colspan="3"><strong>Mass Flow Meter Readings</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col" style="width: 18.5%;" colspan="4"><strong>After Refilling of Cascade</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" style="width: 16%;"><strong>Gas filled at MGS</strong></th>
                            </tr>
                        </thead>
                    </table> -->
                    <div class='table_div'>
                        <table id="mytable" class="table table-striped table-bordered">
                            <thead>
                                <!-- <tr>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong></strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong>Before Refilling of Cascade</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="3"><strong>Mass Flow Meter Readings</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col" colspan="4"><strong>After Refilling of Cascade</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item"><strong>Gas filled at MGS</strong></th>
                                </tr> -->
                                <tr>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Month</strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Time</strong></th>
                                    
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>LCV Number </strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Mother Gas Station </strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Daughter Booster Station </strong></th>
                                    <th bgcolor=" #808080" class="header option-item" scope="col"><strong>Pressure gauge @LCV Before Filling(Bar) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Temperature gauge @LCV Before Filling(Degree C) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Volume of LCV Before Filling (WL) </strong></th>
                                    <th bgcolor=" #F8A000" class="header option-item" scope="col"><strong>Mass of Gas Before Filling in lCV  (Kg) </strong></th>
                                    <th bgcolor=" #A8A000" class="header option-item" scope="col"><strong> Amount of Gas Filled in lCV (Through MFM) (Kg) </strong></th>
                                    <th bgcolor=" #808080" class="header option-item" scope="col"><strong> Pressure gauge @LCV After Filling(Bar) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Temperature gauge @LCV After Filling (Degree C) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong> Volume of LCV After Filling (WL) </strong></th>
                                    <th bgcolor=" #F8A000" class="header option-item" scope="col"><strong> Mass of Gas After Filling in LCV (Kg) </strong></th>
                                    <th bgcolor="#984352" class="header option-item" scope="col"><strong> Total Gas is LCV at MGS(Kg) </strong></th>


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
                                $stmt = $db->prepare("SELECT Cascade_Capacity,MONTHNAME(date_reading) month, date_reading,time(create_date) time ,lcv_id,station_id,dbs_station_id,before_filing_at_mgs_value_pressure_gauge_read,
                                before_filing_at_mgs_value_temperature_gauge_read,before_filing_at_mgs_mass_cng,after_filling_at_mgs_mfm_value_read,time_taken_to_fill_lcv,
                                (TIME_TO_SEC(TIMESTAMPDIFF(SECOND,start_fill_time , end_fill_time ))/3600)*after_filling_at_mgs_mfm_value_read total_mass_through_mfm,after_filling_at_mgs_value_pressure_gauge_read,
                                after_filling_at_mgs_value_temperature_gauge_read,after_filling_at_mgs_mass_cng,total_gas_mgs
                                from luag_transaction_master_dbs_station ,reg_lcv where before_filing_at_mgs_value_temperature_gauge_read	is not NULL
                                and lcv_id=Lcv_Num order by sl_no desc");
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
                                            <?php echo round($row["before_filing_at_mgs_value_pressure_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["before_filing_at_mgs_value_temperature_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["Cascade_Capacity"]); ?>
                                        </td>
                                        <td bgcolor="#F8A000">
                                            <?php echo round($row["before_filing_at_mgs_mass_cng"], 2); ?>
                                        </td>


                                        <td bgcolor="#A8A000"><?php
                                                                if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                                                    echo round($row["total_mass_through_mfm"], 2);
                                                                } else {

                                                                    echo round($row["after_filling_at_mgs_mfm_value_read"], 2);
                                                                } ?>
                                        </td>
                                        <td bgcolor="#808080">
                                            <?php echo round($row["after_filling_at_mgs_value_pressure_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["after_filling_at_mgs_value_temperature_gauge_read"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["Cascade_Capacity"]); ?>

                                        </td>
                                        <td bgcolor="#F8A000">
                                            <?php
                                            if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                                echo round($row["after_filling_at_mgs_mass_cng"], 2);
                                            } else {

                                                echo round($row["after_filling_at_mgs_mfm_value_read"], 2);
                                            } ?>
                                        </td>
                                        <td bgcolor="#984352" style="color: white;">
                                            <?php

                                            if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                                echo round($row["after_filling_at_mgs_mass_cng"] - $row["before_filing_at_mgs_mass_cng"], 2);
                                            } else {

                                                echo round($row["after_filling_at_mgs_mfm_value_read"] + $row["before_filing_at_mgs_mass_cng"], 2);
                                            } ?>

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