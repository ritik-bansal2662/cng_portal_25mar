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
        th,
        td {
            padding: 5px;
            text-align: center;
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

        .gas_recieved_content, .gas_sold_content {
            /* margin: 20px; */
            color: black;
            display: none;
            /* border: 2px solid black; */
            /* border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 30px; */
        }

        .content-active {
            display: block;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="page-wrapper main-content">
        <div class="page-breadcrumb">
            <div class="align-self-center">
                <h2 style="color:white;">Reconciliation Data at Daughter Booster Station</h2>
            </div>
        </div>

        <div class='reg-main'>
            <div class='gas_recieved_tab main-active'>Gas Recieved</div>
            <div class='gas_sold_tab'>Gas Sold</div>
        </div>
        
        <div class="container-fluid" style="background-color: #fff999;">
            <div class=''>
                <div class='gas_recieved_content content-active'>
                    <div id='download_excel_div'>
                        <button id='download_excel1' class='btn btn-primary'>Download Report</button>
                    </div>
                    <div class="table_div">
                        <table id="mytable1" class="w3-table-all w3-small">
                            <thead>
                                <tr>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Month</strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Mass of Gas in LCV Before Emptying at DBS(Kg) </strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Mass of Gas in LCV After Emptying at DBS(Kg)</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Gas Received at DBS (Kg)</strong></th>



                                </tr>

                            </thead>
                            <tbody style="background-color: #E9C006; ">
                                <?php
                                $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");

                                $stmt = $db->prepare("SELECT date_reading,MONTHNAME(date_reading) month,
                                sum(`before_empty_at_db_mass_cng`) total_before_empty_at_db_mass_cng, 
                                sum(`after_empty_at_dbs_mass_cng`) total_after_empty_at_dbs_mass_cng,
                                sum(before_empty_at_db_mass_cng-after_empty_at_dbs_mass_cng) total_gas_received_at_mgs 
                                from luag_transaction_master_dbs_station where `lcv_from_mgs_to_dbs`='6' 
                                group by date_reading order by sl_no desc");
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
                                            <?php echo round($row["total_before_empty_at_db_mass_cng"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["total_after_empty_at_dbs_mass_cng"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php echo round($row["total_gas_received_at_mgs"], 2); ?>
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

                <div class='gas_sold_content'>
                    <div id='download_excel_div'>
                        <button id='download_excel2' class='btn btn-primary'>Download Report</button>
                    </div>
                    <div class="table_div">
                        <table id="mytable2" class="w3-table-all w3-small">
                            <thead>
                                <tr>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>Date</strong></th>
                                    <th bgcolor=" #02603E" class="header" scope="col"><strong>DBS Station</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Gas Received at DBS (Kg)</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Mass of Gas in Stationary Cascade (Kg)</strong></th>
                                    <th bgcolor=" #02603E" class="header option-item" scope="col"><strong>Total Mass of Gas Sale Daily from Dispenser (Kg) </strong></th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #E9C006;">
                                <?php
                                try {
                                    $db = new PDO("mysql:host=localhost;dbname=cng_luag", "root", "");

                                    class Customers
                                    {
                                        private $servername = "localhost";
                                        private $username   = "root";
                                        private $password   = "";
                                        private $dbname     = "cng_luag";
                                        public  $con;

                                        // Database Connection 
                                        public function __construct()
                                        {
                                            try {
                                                $this->con = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
                                            } catch (Exception $e) {
                                                echo $e->getMessage();
                                            }
                                        }

                                        public function updateData()
                                        {
                                            $query = "Insert ignore into luag_dispenser_reading 
                                        (reading_timestamp,date_reading,dbs_station_id,cascade_mass,dispenser_id,dispenser_read,diff_dispenser_reading)
                                        SELECT create_date,date(create_date),station_id,`mass_of_gas`,dispenser_id,dispenser_read, 
                                        ((dispenser_read) - LAG(dispenser_read) OVER (PARTITION BY dispenser_id ORDER BY sl_no ))
                                        dispenser_diff FROM `luag_transaction_dbs_dispenser_cascade`  

                                        GROUP by dispenser_id,date_reading";
                                            $sql = $this->con->query($query);
                                            if ($sql == true) {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        }
                                    }
        //already commented
                                    // New Query to try to get summary Dec 3rd 2021
                                    //Query to connect transaction and Dispenser
                                    //  SELECT a.date_reading, sum(a.before_empty_at_db_mass_cng-a.after_empty_at_dbs_mass_cng) total_gas_received_at_mgs ,b.mass_of_gas from luag_transaction_master_dbs_station a , luag_transaction_dbs_dispenser_cascade b where a.lcv_from_mgs_to_dbs='6' and a.dbs_station_id=b.station_id and a.date_reading=b.date_reading group by a.date_reading order by a.sl_no desc;
                                    //Query to get sum of dispenser reading from previous date
                                    //SELECT `date_reading`,sum(`dispenser_read`) FROM `luag_transaction_dbs_dispenser_cascade` where date_reading < (select date_reading from luag_transaction_dbs_dispenser_cascade order by date_reading desc LIMIT 1) group by date_reading;
                                    // Look into https://www.geeksengine.com/database/subquery/return-rows-of-values.php


                                    // SELECT date_reading,a.dispenser_id,dispenser_read,((a.dispenser_read) - LAG(a.dispenser_read) OVER (PARTITION BY dispenser_id ORDER BY a.sl_no )) DIFF_to_Prev FROM `luag_transaction_dbs_dispenser_cascade` a WHERE a.dispenser_id is not Null GROUP by dispenser_id,date_reading

                                    //    SELECT a.create_date,a.station_id,sum(b.before_empty_at_db_mass_cng-b.after_empty_at_dbs_mass_cng) total_gas_received_at_dbs,a.mass_of_gas,a.dispenser_id,a.dispenser_read,((a.dispenser_read) - LAG(a.dispenser_read) OVER (PARTITION BY a.dispenser_id ORDER BY a.sl_no )) DIFF_to_Prev FROM `luag_transaction_dbs_dispenser_cascade` a,luag_transaction_master_dbs_station b where a.date_reading=b.date_reading GROUP by a.dispenser_id,a.date_reading,a.station_id,a.dispenser_read
        // till here                   
                                    $customerObj = new Customers();
        //already commented              
                    // $customers = $customerObj->updateData();
        // till here                   
                                    $customers = $customerObj->updateData();

        //already commented                            
                                    // $stmt = $db->prepare("SELECT * FROM (SELECT a.date_reading,dbs_station_id,
                                    // sum(before_empty_at_db_mass_cng-after_empty_at_dbs_mass_cng ) total_gas_received_at_dbs 
                                    // FROM luag_transaction_master_dbs_station a where 
                                    // (after_filling_at_mgs_mass_cng-before_filing_at_mgs_mass_cng) !='null' 
                                    // GROUP by(a.date_reading)) as t1,(SELECT `date_reading`,`cascade_mass`,`dbs_station_id`, 
                                    // sum(`diff_dispenser_reading`) as total_sale FROM `luag_dispenser_reading` 
                                    // GROUP by date(`date_reading`),`dbs_station_id`) t2 where t1.dbs_station_id=t2.dbs_station_id 
                                    // and t1.date_reading=t2.date_reading
                                    // ");
                                    // $stmt->execute();

                                    // $stmt->execute();
        //till here                            
                                    $sql = "CALL dispenser_reading()";
                                    $stmt = $db->query($sql);
                                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    die("Error occurred:" . $e->getMessage());
                                }
                                while ($row = $stmt->fetch()) {
                                ?>
                                    <tr>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo $row["date_reading"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo $row["dbs_station_id"]; ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo round($row["total_gas_dbs_calc"], 2); ?>
                                        </td>
                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo $row["cascade_mass"]; ?>
                                        </td>

                                        <!-- <td bgcolor="#E9C006"> -->
                                        <td bgcolor="#d5c47a">
                                            <?php 
                                                echo round($row["total_sale"], 2); ?>
                                        </td>
                                    </tr>
                                <?php
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
        $('#download_excel1').click(function() {
            var table2excel = new Table2Excel();
            table2excel.export($("#mytable1"));
        })
    </script>
    <script>
        $("#mytable").ddTableFilter();
    </script>

    <script>
    $("#mytable2").ddTableFilter();
    </script>

    <script>
        $(".gas_recieved_tab").click(function(){
            console.log("recieved_tab vlicked")
            $(this).addClass('main-active');
            $('.gas_sold_tab').removeClass('main-active');
            $('.gas_recieved_content').addClass('content-active');
            $('.gas_sold_content').removeClass('content-active');
        })


        $(".gas_sold_tab").click(function(){
            console.log("gas_sold_tab vlicked")
            $(this).addClass('main-active');
            $('.gas_recieved_tab').removeClass('main-active');
            $('.gas_sold_content').addClass('content-active');
            $('.gas_recieved_content').removeClass('content-active');
        })
    </script>
    
    
    <?php include('footer.php'); ?>


</body>

</html>