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
    <title>LCV Turnaround Time </title>
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
        /* table{
             */

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
    <div class='page-wrapper main-content'>
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h2 style="color:white;">Daywise LCV Turnaround Time</h2>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #fff999;">
            <div class="w3-container">
                <div id='download_excel_div'><button id='download_excel' class='btn btn-primary'>Download Report</button></div>
                <div class="w3-responsive table_div">
                    <!-- <h2 align="center">Daywise LCV Turnaround Time</h2> -->
                    <table id="mytable" class="w3-table-all w3-small">
                        <thead>
                        <tr>
                            <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Slno</strong></th>
                            <!-- <th bgcolor="#02603E" class="header" scope="col"><strong> LCV Number </strong></th>
                            <th bgcolor="#02603E" class="header" scope="col"><strong> Mother Gas Station </strong></th>
                            <th bgcolor="#02603E" class="header" scope="col"><strong> Daughter Booster Station </strong></th> -->
                            <th bgcolor=" #02603E" class="header" scope="col"><strong>Month</strong></th>
                            <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>

                            <th bgcolor="#02603E" class="header" scope="col"><strong> LCV Number</strong></th>
                            <th bgcolor="#02603E" class="header" scope="col"><strong>MGS Operator</strong></th>
                            <th bgcolor="#02603E" class="header" scope="col"><strong>DBS Operator</strong></th>

                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Vehicle Reaching @MGS</strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Waiting time @MGS </strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Filling time @MGS </strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Vehicle Leaving @MGS</strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Travel time from MGS to DBS </strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Vehicle Reaching @DBS</strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Waiting time @DBS </strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Emptying time @DBS </strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Vehicle Leaving @DBS</strong></th>
                            <th bgcolor="#02603E" class="header option-item" scope="col"><strong>Total time taken </strong></th>

                            <!-- <th bgcolor="#02603E" class="header" scope="col"><strong>Manager Id </strong></th> -->
                            <!-- <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Time Taken to Approve Notification </strong></th> -->

                            </tr>

                        </thead>
                        <tbody style="background-color: #E9C006; ">
                            <?php
                            $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");

                            function timeDiff($firstTime, $lastTime)
                            {
                                // convert to unix timestamps
                                $firstTime = strtotime($firstTime);
                                $lastTime = strtotime($lastTime);

                                // perform subtraction to get the difference (in seconds) between times
                                $timeDiff = $lastTime - $firstTime;

                                // return the difference
                                return $timeDiff;
                            }
                            $stmt = $db->prepare("SELECT date_reading,MONTHNAME(date_reading) month,lcv_id,operator_id,
                            create_date,time(create_date) as start_transaction ,time(end_fill_time) leave_mgs,
                            time(mgs_to_dbs_reach_time) reach_dbs,time(end_empty_time) leave_dbs,
                            TIMEDIFF( `start_fill_time`, `create_date`) mgs_read,
                            TIMEDIFF( `end_fill_time`, `start_fill_time`) filling_time,
                            TIMEDIFF( `mgs_to_dbs_reach_time`, `end_fill_time`) travel_time,
                            TIMEDIFF( `start_empty_time`, `mgs_to_dbs_reach_time`) wait_time,
                            TIMEDIFF( `end_empty_time`, `start_empty_time`) empty_time ,
                            TIMEDIFF( `end_empty_time`, `create_date`) total_time ,
                            start_fill_time,operator_id_at_dbs,
                            end_fill_time,
                            time_taken_to_fill_lcv,
                            start_empty_time,
                            end_empty_time,
                            mgs_to_dbs_reach_time ,
                            update_date 
                            from luag_transaction_master_dbs_station 
                            ORDER BY 	sl_no  desc");
                            $stmt->execute();
                            $i = 1;
                            while ($row = $stmt->fetch()) {
                            ?>
                                <tr>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $i; ?>
                                    </td>
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
                                        <?php echo $row["lcv_id"]; ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo substr($row["operator_id"], 3); ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo substr($row["operator_id_at_dbs"], 3); ?>
                                    </td>

                                    <td bgcolor="#808080">
                                        <?php echo $row["start_transaction"]; ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["mgs_read"]; ?>
                                    </td>

                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["filling_time"]; ?>
                                    </td>
                                    <td bgcolor="#808080">
                                        <?php echo $row["leave_mgs"]; ?>
                                    </td>
                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["travel_time"]; ?>
                                    </td>
                                    <td bgcolor="#808080">
                                        <?php echo $row["reach_dbs"]; ?>
                                    </td>

                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["wait_time"]; ?>
                                    </td>

                                    <!-- <td bgcolor="#E9C006"> -->
                                    <td bgcolor="#d5c47a">
                                        <?php echo $row["empty_time"]; ?>
                                    </td>
                                    <td bgcolor="#808080">
                                        <?php echo $row["leave_dbs"]; ?>
                                    </td>
                                    <td bgcolor="#984352" style="color: white;">
                                        <?php echo $row["total_time"]; ?>
                                    </td>



                                    <!-- <td bgcolor="#E9C006">

                                        if ($row["Notification_MGS"] == "MGS001") {
                                            echo "Manager1";
                                        } else  if ($row["Notification_MGS"] == "MGS002") {
                                            echo "Manager2";
                                        } else  if ($row["Notification_MGS"] == "NA" && $row["Notification_DBS"] == "DBS001") {
                                            echo "Manager3";
                                        } else  if ($row["Notification_MGS"] == "NA" && $row["Notification_DBS"] == "DBS002") {
                                            echo "Manager4";
                                        } 
                                    -->



                                </tr>
                            <?php
                                $decimalHours = 0;
                                $i++;
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