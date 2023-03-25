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
    <title>LCV</title>
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

        table th {
            background-color: gray;
        }

        th, td{
            padding: 5px;
            text-align: center;
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
                <h2 style="color:white;">Reconciliation Data at LCV</h2>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #fff999;">
            <div class="w3-container ">
                <!-- <h2 align="center">Reconciliation Data at LCV</h2> -->
                <div id='download_excel_div'><button id='download_excel' class='btn btn-primary'>Download Report</button></div>
                <div class="w3-responsive table_div">
                    <table id="mytable" class="w3-table-all w3-small">
                        <thead>
                            <tr>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Month</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Transaction start time</strong></th>
                                <th bgcolor="#02603E" class="header" scope="col"><strong> LCV Number </strong></th>
                                <th bgcolor="#02603E" class="header" scope="col"><strong> Mother Gas Station </strong></th>
                                <th bgcolor="#02603E" class="header " scope="col"><strong> Daughter Booster Station </strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas Before Filling in Cascade (Kg)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas Filled at MGS (Kg)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas in LCV After Filling (Kg)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas in LCV Before Emptying (Kg)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas left in Cascade after Delivery (Kg) </strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Amount of Gas Delivered at DBS (Kg) </strong></th>
                                <th bgcolor="#984352" style="color: white;" class="header option-item" scope="col"><strong> Gas Loss during Transportation (Kg) </strong></th>


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
                            $stmt = $db->prepare("SELECT *,MONTHNAME(date_reading) month,time(create_date) as trans_time from luag_transaction_master_dbs_station where `lcv_from_mgs_to_dbs`='6'
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
                                        <?php echo $row["trans_time"]; ?>
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
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["before_filing_at_mgs_mass_cng"]; ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php
                                        if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                            echo round($row["after_filling_at_mgs_mass_cng"], 2);
                                        } else {

                                            echo round($row["after_filling_at_mgs_mfm_value_read"], 2);
                                        } ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php
                                        if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                            echo round($row["after_filling_at_mgs_mass_cng"] - $row["before_filing_at_mgs_mass_cng"], 2);
                                        } else {

                                            echo round($row["after_filling_at_mgs_mfm_value_read"] + $row["before_filing_at_mgs_mass_cng"], 2);
                                        } ?>
                                    </td>
                                
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["before_empty_at_db_mass_cng"]; ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo round($row["after_empty_at_dbs_mass_cng"], 2); ?>

                                    </td>

                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo round((float)$row["before_empty_at_db_mass_cng"] - ((float)$row["after_empty_at_dbs_mass_cng"]), 2); ?>
                                    </td>
                                    
                                    <td bgcolor="#984352" style="color: white;">
                                        <?php
                                        if( $row["date_reading"]>='2022-02-15'){
                                        if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                            echo round(($row["after_filling_at_mgs_mass_cng"] - $row["before_filing_at_mgs_mass_cng"])-($row["before_empty_at_db_mass_cng"]), 2);
                                        } else {

                                            echo round(($row["after_filling_at_mgs_mfm_value_read"] + $row["before_filing_at_mgs_mass_cng"])-($row["before_empty_at_db_mass_cng"]), 2);
                                        } }else {
                                
                                        if ($row["after_filling_at_mgs_mfm_value_read"] == 1) {
                                            echo round($row["after_filling_at_mgs_mass_cng"] - $row["before_filing_at_mgs_mass_cng"]- ($row["before_empty_at_db_mass_cng"] - $row["after_empty_at_dbs_mass_cng"]), 2);
                                        } else {

                                            echo round($row["after_filling_at_mgs_mfm_value_read"] - $row["before_filing_at_mgs_mass_cng"]- ($row["before_empty_at_db_mass_cng"] - $row["after_empty_at_dbs_mass_cng"]), 2);
                                        }} ?>
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

    <?php include('footer.php'); ?>


</body>

</html>