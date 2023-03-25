<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<!-- select Notification_Date,Notification_LCV,GROUP_CONCAT(distinct(Notification_MGS)),Notification_DBS,GROUP_CONCAT(distinct(`operator_id`)),GROUP_CONCAT(distinct(`Notification_approver`)), max(case when flag = 1 then TIMEDIFF( `update_date`, `create_date`) end) at_mgs, max(case when flag = 2 then TIMEDIFF( `update_date`, `create_date`) end) bfr_filling, max(case when flag = 3 then TIMEDIFF( `update_date`, `create_date`) end) aftr_filling, max(case when flag = 4 then TIMEDIFF( `update_date`, `create_date`) end) at_dbs , max(case when flag = 5 then TIMEDIFF( `update_date`, `create_date`) end) bfr_empty, max(case when flag = 6 then TIMEDIFF( `update_date`, `create_date`) end) aftr_empty from notification group by `Notification_Date`,`Notification_DBS`,`Notification_LCV`
order by `Notification_Date` desc -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
    <title>Reconciliation Data at Organization Level</title>
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

        table{
            /* margin: 20px; */
            /* width: 95%; */
            /* margin-left: auto;
            margin-right: auto; */
        }

        table,
        th,
        td {
            border: 1px solid white;
            vertical-align: middle;
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
                <h2 style="color:white;">Daywise Notification Approvel Lag Report</h2>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #fff999;">
            <div class="w3-container ">
                <div id='download_excel_div'><button id='download_excel' class='btn btn-primary'>Download Report</button></div>
                <div class="w3-responsive">
                    <!-- <h2 align="center">Daywise Notification Approvel Lag Report</h2> -->
                    <div class="table_div">
                        <table id="mytable" class="w3-table-all w3-small">
                            <thead>
                            <tr>
                                <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Slno</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Month</strong></th>
                                <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                <th bgcolor="#02603E" class="header" scope="col"><strong> LCV Number </strong></th>
                                <th bgcolor="#02603E" class="header" scope="col"><strong> Mother Gas Station </strong></th>
                                <th bgcolor="#02603E" class="header" scope="col"><strong> Daughter Booster Station </strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> LCV @MGS</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Before filling LCV @MGS<br>(HH:MM:SS)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> After filling LCV @MGS<br>(HH:MM:SS)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> LCV @DBS<br>(HH:MM:SS)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> Before emptying LCV @DBS<br>(HH:MM:SS)</strong></th>
                                <th bgcolor="#02603E" class="header option-item" scope="col"><strong> After emptying LCV @DBS<br>(HH:MM:SS)</strong></th>
                                </tr>

                            </thead>
                            <tbody style="background-color: #E9C006; ">
                                <?php
                                $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");
                                $trans = '1. At MGS,2. Before Filling,3. After Filling,4. At DBS,5. Before Emptying,6. After Emptying';

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
                                $stmt = $db->prepare("select Notification_Date date,MONTHNAME(Notification_Date) month,Notification_LCV lcv_id,
                                GROUP_CONCAT(DISTINCT(Notification_MGS)) mgs_id,
                                GROUP_CONCAT(DISTINCT(Notification_DBS)) dbs_id,
                                GROUP_CONCAT(DISTINCT(`operator_id`)) operator_id,
                                GROUP_CONCAT(DISTINCT(`Notification_approver`)) approver_id, 
                                GROUP_CONCAT(case when flag = 1 then TIMEDIFF( `update_date`, `create_date`) end) at_mgs, 
                                GROUP_CONCAT(case when flag = 2 then TIMEDIFF( `update_date`, `create_date`) end) bfr_filling,
                                GROUP_CONCAT(case when flag = 3 then TIMEDIFF( `update_date`, `create_date`) end) aftr_filling,
                                GROUP_CONCAT(case when flag = 4 then TIMEDIFF( `update_date`, `create_date`) end) at_dbs
                                , GROUP_CONCAT(case when flag = 5 then TIMEDIFF( `update_date`, `create_date`) end) bfr_empty, 
                                GROUP_CONCAT(case when flag = 6 then TIMEDIFF( `update_date`, `create_date`) end) aftr_empty 
                                from notification group by `Notification_Date`,`Notification_DBS`,`Notification_LCV` ORDER BY 	Notification_Id  desc");

                                // SELECT `Notification_Date`,`Notification_LCV`,
                                // GROUP_CONCAT(`status`) status, 
                                // GROUP_CONCAT(TIMEDIFF( `update_date`, `create_date`)) lag 
                                // FROM `notification` GROUP BY Notification_Date,`Notification_LCV`
                                // ORDER BY 	Notification_Id  desc");
                                // $stmt = $db->prepare("SELECT * from notification
                                // ORDER BY 	Notification_Id  desc");
                                $stmt->execute();
                                $i = 1;
                                while ($row = $stmt->fetch()) {
                                    // $lag = preg_replace("/\,/", "<br><hr>", $row['lag']);
                                    // $status = preg_replace("/\,/", "<br><hr>", $row['status']);
                                    // $trans_detail = preg_replace("/\,/", "<br><hr>", $trans);

                                    // $trans_detail = explode(",", $trans);
                                    //  preg_replace("/\,/", "<br>", $trans);
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
                                            <?php echo $row["date"]; ?>
                                        </td>

                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["lcv_id"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["mgs_id"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo $row["dbs_id"]; ?>
                                        </td>

                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php $bfr_filling = explode(',', $row['at_mgs']); //what will do here
                                            foreach ($bfr_filling as $out) {
                                                echo $out . '<br/>';
                                            } ?>


                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php $bfr_filling = explode(',', $row['bfr_filling']); //what will do here
                                            foreach ($bfr_filling as $out) {
                                                echo $out . '<br/>';
                                            } ?>

                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php $bfr_filling = explode(',', $row['aftr_filling']); //what will do here
                                            foreach ($bfr_filling as $out) {
                                                echo $out . '<br/>';
                                            } ?>

                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php $bfr_filling = explode(',', $row['at_dbs']); //what will do here
                                            foreach ($bfr_filling as $out) {
                                                echo $out . '<br/>';
                                            } ?>

                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php $bfr_filling = explode(',', $row['bfr_empty']); //what will do here
                                            foreach ($bfr_filling as $out) {
                                                echo $out . '<br/>';
                                            } ?>

                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php $bfr_filling = explode(',', $row['aftr_empty']); //what will do here
                                            foreach ($bfr_filling as $out) {
                                                echo $out . '<br/>';
                                            } ?>

                                        </td>





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