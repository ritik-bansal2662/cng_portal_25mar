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
    <title>Reconciliation Data at Master Booster Station</title>
    <?php include('head.php'); ?>
    <style>
        container {
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
        th, td {
            text-align: center;
            padding: 5px;
        }
        table th {
            background-color: gray; 
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
                <h2 style="color:white;">Reconciliation Data at Master Booster Station</h2>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #fff999;">
            <div class="w3-container ">
                <!-- <h2 align="center"></h2> -->
                <div id='download_excel_div'><button id='download_excel' class='btn btn-primary'>Download Report</button></div>
                <div class="w3-responsive">
                    <!-- <p>*BRC: Before Refilling of Cascade,*MFMR: Mass Flow Meter Readings,*AFC: After Refilling of Cascade -->
                    </p>
                    <table id="mytable" class="w3-table-all w3-small">
                        <thead>
                            <tr>
                            <th bgcolor=" #02603E" class="header" scope="col"><strong>Month</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                <!-- <th bgcolor=" #02603E" class="header" scope="col"><strong>Time</strong></th> -->

                                <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Volume of LCV (WL) </strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Mass of Gas in LCV Before Filling at MGS(Kg) </strong></th>
                                <!--  <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Amount of Gas Transferred by Flow Meter (Kg)</strong></th>-->
                                <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Mass of Gas After Filling at MGS (Kg) </strong></th>
                                <th bgcolor="#984352" style="color: white;" class="header option-item" scope="col"><strong>Total Gas Filled at MGS (Kg) </strong></th>

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
                            $stmt = $db->prepare("SELECT DATE(date_reading) date,after_filling_at_mgs_mfm_value_read,MONTHNAME(date_reading) month,sum(Cascade_Capacity) total_volume,sum(before_filing_at_mgs_mass_cng) total_before_filing_at_mgs_mass_cng, 
                            sum(after_filling_at_mgs_mfm_value_read) total_after_filling_at_mgs_mass_cng,
                            sum((TIME_TO_SEC(TIMESTAMPDIFF(SECOND,start_fill_time , end_fill_time ))/3600)*after_filling_at_mgs_mfm_value_read) total_gas_filled_at_mgs1,
                            sum(after_filling_at_mgs_mfm_value_read-before_filing_at_mgs_mass_cng) total_gas_filled_at_mgs
                            from luag_transaction_master_dbs_station ,reg_lcv where before_filing_at_mgs_value_temperature_gauge_read	is not NULL
                                                and lcv_id=Lcv_Num group by date_reading order by sl_no desc");
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
                                        <?php echo $row["date"]; ?>
                                    </td>

                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["total_volume"]; ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo round($row["total_before_filing_at_mgs_mass_cng"], 2); ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006">  -->
                                    <td bgcolor="#d5c47a">
                                    <?php
                                        if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                            echo round($row["total_gas_filled_at_mgs1"], 2);
                                        } else {

                                            echo round($row["total_after_filling_at_mgs_mass_cng"], 2);
                                        } 
                                    ?>
                                    
                                    </td>
                                

                                    <td bgcolor="#984352" style="color: white;">
                                        <?php echo round($row["total_gas_filled_at_mgs"], 2); ?>
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

    <script>
        $("#mytable2").ddTableFilter();
    </script>

    <?php include('footer.php'); ?>


</body>

</html>